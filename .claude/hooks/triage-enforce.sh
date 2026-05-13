#!/bin/bash
# .claude/hooks/triage-enforce.sh — installed by MCP Config Manager
# PreToolUse hook — three-tier triage before JS/JSX/TS edits.
#
# Tier 1 (< 500 chars):     Fast Path — ask user: bypass or delegate
# Tier 2 (500-3,000 chars): Researcher Assessment
# Tier 3 (> 3,000 chars):   Standard Triage — full workflow mandatory
#
# AUTHORIZATION:
#   echo "bypass: <reason>" > .claude/triage.lock   (Tier 1/2 Option A)
#   echo "jules-issue-<n>"  > .claude/triage.lock   (Tier 1/2 Option B / Tier 3)
#
# The lock file is cleared automatically by the Stop hook at session end.

INPUT=$(cat)

PARSED=$(python3 -c "
import sys, json
try:
    d = json.loads(sys.argv[1])
    inp = d.get('tool_input', {})
    fp  = inp.get('file_path', '')
    old = inp.get('old_string', '')
    new = inp.get('new_string', inp.get('content', ''))
    size = len(old) + len(new)
    print(fp)
    print(size)
except Exception:
    print('')
    print(0)
" "$INPUT" 2>/dev/null)

FILE_PATH=$(echo "$PARSED" | head -1)
CHANGE_SIZE=$(echo "$PARSED" | tail -1)

[ -z "$FILE_PATH" ] && exit 0

echo "$FILE_PATH" | grep -qiE '\.(jsx?|tsx?)$' || exit 0
echo "$FILE_PATH" | grep -qE '(^|[/\\])\.claude[/\\]' && exit 0
echo "$FILE_PATH" | grep -qE '(build\.js|jest\.config|\.eslintrc|tsconfig|vite\.config|webpack\.config)(\..*)?$' && exit 0
echo "$FILE_PATH" | grep -qE '(vault\.js|keychain\.js|mcp-server\.js|preload\.js|license-client\.js)$' && exit 0

LOCK=".claude/triage.lock"
if [ -f "$LOCK" ]; then
  AUTH=$(cat "$LOCK" | tr -d '\r\n')
  python3 -c "
import json, sys
auth = sys.argv[1]
print(json.dumps({
  'hookSpecificOutput': {
    'hookEventName': 'PreToolUse',
    'additionalContext': '[TRIAGE HOOK] Edit authorized: ' + auth
  }
}))
" "$AUTH"
  exit 0
fi

if [ "$CHANGE_SIZE" -lt 500 ] 2>/dev/null; then
  TIER=1
elif [ "$CHANGE_SIZE" -lt 3000 ] 2>/dev/null; then
  TIER=2
else
  TIER=3
fi

# Notify MCP Manager about the block (for SMS notification)
PORT="7329"
PROJECT_ID="bf8bdb8a"
if [[ "$PORT" =~ ^[0-9]+$ ]]; then
  curl -s -X POST "http://127.0.0.1:$PORT/triage/block"     -H "Content-Type: application/json"     -d "{\"projectId\": \"$PROJECT_ID\", \"tier\": $TIER, \"size\": $CHANGE_SIZE}"     --max-time 2 > /dev/null 2>&1 || true
fi

python3 -c "
import json, sys
tier = int(sys.argv[1])
sz = sys.argv[2]

if tier == 1:
    label = 'Tier 1 Fast Path'
    user_block = '''\U0001f512 **Triage Hook - Tier 1 Fast Path:** I need your approval before making this edit (small, ~''' + sz + ''' chars).

**Option A - Implement directly (bypass triage):** Orchestrator makes the change now. Best for quick fixes, typos, and directly-requested changes.

**Option B - Delegate to Implementer:** Create GitHub Issue, label it for Implementer, arm Watch Monitor. Best for features, refactors, and PR-worthy changes.

Which would you prefer? **(A to bypass / B to delegate)**'''
    internal = 'If A: echo \"bypass: <reason>\" > .claude/triage.lock then retry. If B: gh issue create, gh issue edit <n> --add-label jules, echo \"jules-issue-<n>\" > .claude/triage.lock, arm Watch Monitor.'
elif tier == 2:
    label = 'Tier 2 Researcher Assessment'
    user_block = '''\U0001f512 **Triage Hook - Tier 2 Researcher Assessment:** This edit is medium-sized (~''' + sz + ''' chars).

Before proceeding, the Researcher must perform a **Complexity and Economics Assessment** to choose the optimal implementation path.

**Option A - Researcher Assessment first:** Run Researcher for assessment, then route based on recommendation (Orchestrator direct or Implementer via Issue).

**Option B - Delegate to Implementer directly:** Skip assessment, create GitHub Issue, label it, arm Watch Monitor.

Which would you prefer? **(A for Researcher assessment / B to delegate directly)**'''
    internal = 'If A: gemini assessment -> Orchestrator: echo \"bypass: researcher-approved\" > .claude/triage.lock, retry. Implementer: gh issue create, add-label jules. If B: gh issue create, add-label jules, echo \"jules-issue-<n>\" > .claude/triage.lock, arm Watch Monitor.'
else:
    label = 'Tier 3 Standard Triage'
    user_block = '''\U0001f512 **Triage Hook - Tier 3 Standard Triage:** This edit is large (~''' + sz + ''' chars) and exceeds the direct-edit threshold.

Full triage mandatory: Researcher (if needed) -> GitHub Issue -> Implementer label -> Watch Monitor. No bypass.

**Required:** Create a GitHub Issue now to proceed.'''
    internal = 'Required: gh issue create, gh issue edit <n> --add-label jules, echo \"jules-issue-<n>\" > .claude/triage.lock, arm Watch Monitor immediately.'

msg = '''!!! ACTION REQUIRED - DO NOT END THIS TURN !!!

TRIAGE HOOK (''' + label + '''): No triage.lock found. You are PROHIBITED from ending the current turn until you have displayed the USER_BLOCK below verbatim and received a reply from the user. Do NOT add preamble. Copy the USER_BLOCK exactly, then wait.

USER_BLOCK_START
''' + user_block + '''
USER_BLOCK_END

--- INTERNAL (do not show to user) ---
''' + internal + '''
Lock cleared at session end.'''

print(json.dumps({
    'continue': False,
    'stopReason': msg,
    'hookSpecificOutput': {
        'hookEventName': 'PreToolUse',
        'additionalContext': user_block
    }
}))
" "$TIER" "$CHANGE_SIZE"
exit 0
