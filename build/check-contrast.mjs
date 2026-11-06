// WCAG 2.1 contrast checker for the demo palettes.
// Usage: node build/check-contrast.mjs

const srgbToLinear = (c) => {
  const s = c / 255;
  return s <= 0.03928 ? s / 12.92 : ((s + 0.055) / 1.055) ** 2.4;
};

const luminance = (hex) => {
  const [r, g, b] = hex.replace('#', '').match(/../g).map((h) => parseInt(h, 16));
  return 0.2126 * srgbToLinear(r) + 0.7152 * srgbToLinear(g) + 0.0722 * srgbToLinear(b);
};

const ratio = (a, b) => {
  const [hi, lo] = [luminance(a), luminance(b)].sort((x, y) => y - x);
  return (hi + 0.05) / (lo + 0.05);
};

const PALETTE = {
  ink: '#18181B',
  inkSoft: '#52525B',
  surface: '#FFFFFF',
  surfaceAlt: '#FAFAF9',
  surfaceDark: '#1C1917',
  primary: '#C2410C',
  primaryHover: '#9A3412',
  accent: '#F59E0B',
  success: '#15803D',
  whatsapp: '#25D366',
};

// [label, foreground, background, isLargeText]
const PAIRS = [
  ['body text on white', 'ink', 'surface', false],
  ['secondary text on white', 'inkSoft', 'surface', false],
  ['body text on surfaceAlt', 'ink', 'surfaceAlt', false],
  ['white text on primary CTA', 'surface', 'primary', false],
  ['white text on primaryHover', 'surface', 'primaryHover', false],
  ['white text on surfaceDark', 'surface', 'surfaceDark', false],
  ['accent text on surfaceDark', 'accent', 'surfaceDark', false],
  ['accent text on white (AVOID)', 'accent', 'surface', false],
  ['success text on white', 'success', 'surface', false],
  ['primary link on white', 'primary', 'surface', false],
  ['white text on whatsapp (AVOID)', 'surface', 'whatsapp', false],
  ['ink text on whatsapp', 'ink', 'whatsapp', false],
];

const threshold = (large) => (large ? 3.0 : 4.5);
let failures = 0;

console.log('WCAG 2.1 AA contrast report -- demo1 palette\n');
console.log('pair'.padEnd(34), 'ratio'.padEnd(8), 'need'.padEnd(6), 'verdict');
console.log('-'.repeat(68));

for (const [label, fg, bg, large] of PAIRS) {
  const r = ratio(PALETTE[fg], PALETTE[bg]);
  const need = threshold(large);
  const passAA = r >= need;
  const passAAA = r >= (large ? 4.5 : 7);
  const verdict = passAAA ? 'AAA' : passAA ? 'AA' : 'FAIL';
  if (!passAA && !label.includes('AVOID')) failures += 1;
  console.log(
    label.padEnd(34),
    r.toFixed(2).padEnd(8),
    need.toFixed(1).padEnd(6),
    verdict,
  );
}

console.log('-'.repeat(68));
console.log(failures === 0 ? 'OK: no unexpected failures.' : `${failures} unexpected failure(s).`);
