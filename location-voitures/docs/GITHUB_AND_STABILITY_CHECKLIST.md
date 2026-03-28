# GitHub & Stability Checklist

## 1) Never push secrets or local data
- Keep `.env` local only (already ignored).
- Keep SQL dumps and local logs out of git:
  - `*.sql`
  - `server.log`
  - `server-error.log`
- Before every push run:
  - `powershell -ExecutionPolicy Bypass -File scripts/pre-push-safety.ps1`

## 2) Presentation database without leaking real data
- Preferred: build DB from migrations + seeders (clean demo data):
  - `php artisan migrate:fresh --seed`
- If you must share structure only:
  - share migrations/seeders, not raw production dump.

## 3) Quick recovery after reboot/crash
- Run one command:
  - `powershell -ExecutionPolicy Bypass -File scripts/recover-workspace.ps1`
- Then start app:
  - `php artisan serve`

## 4) Daily safe workflow
1. `git pull`
2. `powershell -ExecutionPolicy Bypass -File scripts/recover-workspace.ps1`
3. work normally
4. `powershell -ExecutionPolicy Bypass -File scripts/pre-push-safety.ps1`
5. `git push`
