# Design System

Every visual decision in the CHF site derives from the tokens in this
document. Use these values verbatim — do not introduce new colors, fonts, or
spacing scales without updating this file first.

---

## 1. Brand colors

All colors are defined as CSS custom properties in `style.css` under `:root`.
Reference them in Elementor via **Global Colors** (already pre-configured on
theme activation) or directly in custom CSS as `var(--token-name)`.

| Name | Token | Hex | Usage |
|---|---|---|---|
| Navy | `--navy` | `#1B2A4A` | Primary brand color, headlines on light, heading text |
| Navy Deep | `--navy-deep` | `#0f1a2e` | Hover state for navy, dark section backgrounds |
| Green | `--green` | `#56B84A` | Primary CTA, accent highlights, links |
| Green Dark | `--green-dark` | `#3d8f35` | Button hover, pressed states |
| Green Light | `--green-light` | `#7dd870` | Gradient terminus (used with Green) |
| Gold | `--gold` | `#C9A84C` | Secondary accent, decorative flourishes |
| White | `--white` | `#ffffff` | Default backgrounds, reversed text |
| Off White | `--off` | `#f7f6f4` | Alternating section backgrounds |
| Rule | `--rule` | `#e5e5e5` | Dividers, borders, input outlines |

**Never** use pure black (`#000`) for text — always `--navy` or a softer
charcoal. Black-on-white is harsh and off-brand.

### Accessibility pairs

Tested against WCAG AA (4.5:1 body, 3:1 large text):

| Foreground | Background | Contrast | Body AA | Large AA |
|---|---|---|---|---|
| `--navy` | `--white` | 13.4:1 | ✅ | ✅ |
| `--navy` | `--off` | 12.8:1 | ✅ | ✅ |
| `--white` | `--navy` | 13.4:1 | ✅ | ✅ |
| `--white` | `--green-dark` | 4.6:1 | ✅ | ✅ |
| `--green-dark` | `--white` | 4.6:1 | ✅ | ✅ |
| `--green` | `--white` | 3.1:1 | ❌ | ✅ |
| `--gold` | `--navy` | 4.8:1 | ✅ | ✅ |

**Important:** `--green` (#56B84A) on white **fails** body AA. Never use it
for body-text links or small labels. Use `--green-dark` instead for any text
smaller than 18px.

---

## 2. Typography

### Font families

| Role | Font | Weight range | Token |
|---|---|---|---|
| Headings | Merriweather (serif) | 400, 700, 900 | `--font-serif` |
| Body + UI | Inter (sans) | 400, 500, 600, 700 | `--font-sans` |

Fonts load from Google Fonts in `functions.php` with `display=swap`. Preconnect
hints are sent early to minimize blocking.

**Fallback stack** (if Google Fonts fails):
- Serif: `Georgia, 'Times New Roman', serif`
- Sans: `system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif`

### Type scale

Headings use CSS `clamp()` for fluid resizing between mobile and desktop with
no breakpoint jumps.

| Element | Desktop | Tablet | Mobile | Formula |
|---|---|---|---|---|
| H1 | 56px | 42px | 32px | `clamp(32px, 5.5vw, 56px)` |
| H2 | 40px | 32px | 26px | `clamp(26px, 4vw, 40px)` |
| H3 | 28px | 24px | 22px | `clamp(22px, 3vw, 28px)` |
| H4 | 22px | 20px | 18px | `clamp(18px, 2.5vw, 22px)` |
| H5 | 18px | 18px | 18px | `18px` |
| H6 | 16px | 16px | 16px | `16px` |
| Body | 18px | 17px | 16px | `clamp(16px, 1.2vw, 18px)` |
| Small | 14px | 14px | 14px | `14px` |

### Font family per level

- **H1, H2, H3** → Merriweather (serif)
- **H4, H5, H6** → Inter (sans) — smaller display headings read better in sans
- **Body, UI, nav, buttons** → Inter

### Line height

- Headings: `1.15`
- Body: `1.6`
- UI / nav: `1.3`

### Max line length

Body `<p>` elements are capped at `72ch` to keep reading comfortable on wide
screens. Defined in `design-system.css`.

---

## 3. Spacing scale

CHF uses a modular spacing scale based on 8px. Use Elementor's built-in
spacing controls and pick values from this scale:

| Step | px | rem | Usage |
|---|---|---|---|
| 0 | 0 | 0 | Reset |
| 1 | 4 | 0.25 | Tight inline gap (icon to text) |
| 2 | 8 | 0.5 | Tag padding |
| 3 | 16 | 1 | Paragraph spacing, small gaps |
| 4 | 24 | 1.5 | Card padding, component gap |
| 5 | 32 | 2 | Section padding (small) |
| 6 | 48 | 3 | Section gap, large component gap |
| 7 | 64 | 4 | Section padding (default) |
| 8 | 96 | 6 | Section padding (large) |
| 9 | 128 | 8 | Hero section vertical padding |

Never use arbitrary numbers like `37px` or `50px`. Round to the nearest scale
value.

---

## 4. Layout

### Container

- **Max width:** 1200px (set on `.e-con` in `elementor-overrides.css`)
- **Horizontal padding:** `clamp(20px, 5vw, 48px)` via `--container-pad` token
- **Alignment:** centered

### Breakpoints

Matches Elementor defaults:

| Name | Range |
|---|---|
| Mobile | up to 767px |
| Tablet | 768–1024px |
| Desktop | 1025px+ |
| Wide | 1440px+ |

---

## 5. Border radius

| Name | Token | Value | Usage |
|---|---|---|---|
| Pill | `--radius-pill` | 28px | Buttons, tag pills |
| Medium | `--radius-md` | 12px | Cards, input fields, images |
| Small | _(inline)_ | 6px | Small badges |
| None | _(inline)_ | 0 | Section dividers, rules |

---

## 6. Shadows

| Name | Token | Value |
|---|---|---|
| Default | `--shadow` | `0 4px 24px rgba(0,0,0,0.06)` |
| Green glow | `--green-glow` | `0 8px 32px rgba(86,184,74,0.25)` |

Avoid multi-layer shadows. One shadow per element, low opacity, soft blur.

---

## 7. Motion / easing

| Name | Token | Value | Usage |
|---|---|---|---|
| Spring | `--ease-spring` | `linear(...)` (see style.css) | Elastic button press |
| Expo Out | `--ease-out-expo` | `cubic-bezier(0.16, 1, 0.3, 1)` | Hero reveal, page enter |
| Quart Out | `--ease-out-quart` | `cubic-bezier(0.25, 1, 0.5, 1)` | Default UI transition |

### Duration

- **Hover / focus:** 150–200ms
- **Card lift / lift in:** 300–400ms
- **Page reveal / scroll fade:** 600–800ms
- **Counter animation:** 1800ms

### Reduced motion

`frontend.js` and `design-system.css` both honor
`@media (prefers-reduced-motion: reduce)`. Animations either run instantly or
cross-fade instead of moving. Do not override this.

---

## 8. Components

### Button — Primary

- Background: `--green`
- Text: `--white`, Inter 600 weight
- Padding: `12px 28px`
- Border radius: `--radius-pill` (28px)
- Hover: background `--green-dark`, translateY(-1px)
- Focus: `--focus-ring` outline (`0 0 0 3px rgba(86,184,74,0.5)`)

### Button — Outline

- Background: transparent
- Border: 2px solid `--navy`
- Text: `--navy`, Inter 600 weight
- Padding: `10px 26px` (slight adjustment for border)
- Hover: background `--navy`, text `--white`

### Button — Text

- Background: none
- Text: `--green-dark`, Inter 600, underlined
- No padding
- Hover: text `--green`, underline thickens

### Card

- Background: `--white`
- Border: 1px solid `--rule`
- Border radius: `--radius-md` (12px)
- Padding: 24px (mobile) / 32px (desktop)
- Hover: translateY(-2px), shadow upgrades to `--shadow`

### Section (dark)

- Background: `--navy` or `--navy-deep`
- Text: `--white` (body) and `--green` or `--gold` (accents)
- Vertical padding: 96px desktop / 64px tablet / 48px mobile

### Section (off-white)

- Background: `--off`
- Text: `--navy`
- Vertical padding: same as dark

---

## 9. Icon system

The theme does not ship its own icon font. Use **Elementor's built-in
Font Awesome 6** (included with Elementor Pro) or SVG inline where precision
matters.

- **Icon color on light:** `--navy` or `--green-dark`
- **Icon color on dark:** `--white` or `--green`
- **Standard size:** 20px (inline) or 24px (prominent)
- **Stroke width:** 2px for outline icons

---

## 10. Do-not-dos

- ❌ Don't use `box-shadow` with more than 2 layers
- ❌ Don't add new colors without updating `style.css` tokens first
- ❌ Don't use Google Fonts other than Inter and Merriweather
- ❌ Don't use full black (`#000`) for text or borders
- ❌ Don't use italic for emphasis in body — use `<strong>` or `--green-dark`
- ❌ Don't animate anything longer than 1000ms on user interaction
- ❌ Don't override `--font-sans` or `--font-serif` on individual elements
- ❌ Don't use `!important` in any CSS file — the cascade is designed to work
