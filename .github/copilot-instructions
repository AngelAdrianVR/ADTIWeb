# Project Context: Industrial Automation Web Redesign

## Business Domain & Audience
- **Niche:** Industrial automation, industrial design, machinery programming (HMI, SCADA/Ignition), and control panel manufacturing.
- **Target Audience:** The Americas (North, Central, and South America).
- **Languages:** Bilingual (Spanish and English).

## Tech Stack
- Backend: Laravel 12 (PHP 8.2+)
- Frontend: Vue 3 (Composition API, `<script setup>`) + Inertia.js v2
- Localization: i18n integration (Vue I18n or Laravel localization via Inertia)
- Build Tool: Vite
- Styling: Tailwind CSS
- Animations: GSAP, VueUse Motion, Smooth Scroll (Lenis)

## Design System & UX (Strict)
- **Vibe:** Premium, professional, and high-tech (TESLA-like aesthetic). Corporate brutalism / High-tech minimalism.
- **Color Palette (High-Contrast / Premium Tech):**
  - `Deep Onyx` (e.g., `#0F172A` or `#111111`): Deep dark background for the premium, high-tech, screen-focused feel.
  - `Brand Blue` (e.g., `#0082A8`): Extracted from the ADTI logo. Primary accent for primary buttons, active states, and key highlights.
  - `Electric Cyan` (e.g., `#00D4FF`): A brighter, luminous variation of the brand blue. Used sparingly for hover states, subtle glowing effects, and GSAP animation highlights to keep the UI dynamic and modern.
  - `Steel Grey` (e.g., `#6D6E71`): Extracted from the ADTI logo. Used for secondary text, borders, subtle UI panel backgrounds, and inactive elements.
  - `Ghost White` (e.g., `#F8FAFC`): Primary text color on dark backgrounds to ensure crisp readability and stark contrast.
- **Layout & Structure:** 100% Mobile-responsive. Highly organized and intuitive structure for immediate user comprehension. Maximize whitespace; avoid clutter.
- **Performance-First Animations:** Animations MUST be lightweight, hardware-accelerated (CSS transforms/opacity), and optimized to run flawlessly without lag on low-end devices.
- **Typography:** Large, clean, geometric sans-serif typography.

## Development Rules
1. **Efficiency:** Output only the necessary code. Avoid redundant comments or over-explaining.
2. **Token Economy:** When updating existing files, provide only the modified code blocks, not the entire file, unless explicitly requested.
3. **Inertia.js:** Use Inertia `<Link>` for navigation. Pass only strictly required data from Laravel controllers as props to Vue.
4. **Vue 3:** Exclusively use `<script setup>`. Extract reusable logic into composables.
5. **Laravel:** Follow PSR-12. Keep controllers lean, use FormRequests for validation.
6. **Styling:** Use Tailwind CSS utility classes. Avoid writing custom CSS unless absolutely required for complex animations. Use custom Tailwind color configuration mapping to the defined palette.