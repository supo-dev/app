## Termwind / TUI Views
- This application uses Termwind to render Blade views in the terminal as a TUI (Terminal User Interface).
- **Unsupported Tailwind classes**: Termwind does not support all Tailwind CSS classes. The following classes are confirmed NOT to work:
  - `text-sm`, `text-xs`, `text-lg`, `text-xl`, etc. (font size modifiers beyond base)
  - `border-t`, `border-b`, `border-l`, `border-r` (directional borders)
  - Many other advanced Tailwind utilities may not be supported
- **Supported classes**: Basic utilities like `text-white`, `text-gray`, `flex`, `space-x-*`, `p-*` (padding), `w-*`, `font-bold` work well.
- **CRITICAL: Always re-run commands after making changes** - You must run `php servers/testing.php \\App\\Console\\Commands\\{CommandClass}` after every design change to verify the output looks correct in the terminal. Continue iterating and re-running until the design is perfect.
- When designing TUI views, iterate multiple times, re-running the command each time to check your changes, until you are 100% confident the design is really good and polished.

## Comments
- **Only use PHPDoc comments that provide typing information.**
- Never use descriptive comments explaining what code does - the code should be self-explanatory.
- Never use empty comments like `//` or `/* */`.
- Never use inline comments within code unless there is something _very_ complex going on.

## PHPDoc Blocks
- **Required PHPDoc**: `@return` for complex return types, `@var` for typed properties, `@property-read` for magic properties, `@use` for trait generics, `@param` only when type is complex.
- **Allowed PHPDoc**: Array shape definitions like `@return array{user: User, token: string}`.
- **Not allowed**: Descriptive comments like "Execute the console command", "Create a new instance", "Get the user's name", etc.

# IMPORTANT: Testing Commands
- When testing commands, you should **always** run: `php servers/testing.php \\App\\Console\\Commands\\{CommandClass}`
