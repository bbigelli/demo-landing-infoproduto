// Builds the importable Elementor template for the "Treino em Casa 30 Dias" landing page.
//
//   node build/generate-elementor.mjs
//
// Reads build/content.json (all pt-BR copy lives there, never in this file) and writes
// elementor/landing-treino-30-dias.json. Keeping copy out of the generator means a new
// client is a new content.json, not a new generator.

import { readFileSync, writeFileSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import { dirname, join } from 'node:path';
import {
  accordion, button, container, esc, heading, image,
  resetIds, starRating, template, text,
} from './elementor-kit.mjs';

const ROOT = join(dirname(fileURLToPath(import.meta.url)), '..');
const c = JSON.parse(readFileSync(join(ROOT, 'build', 'content.json'), 'utf8'));

// Any WordPress install resolves this path, so the template imports without broken images.
const PLACEHOLDER = '/wp-content/plugins/elementor/assets/images/placeholder.png';

const waLink = `https://wa.me/${c.whatsapp.phone}?text=${encodeURIComponent(c.whatsapp.message)}`;

/** Section wrapper: outer container paints the background, inner one holds the content. */
const section = (modifier, children) =>
  container({
    cls: `tc-section tc-section--${modifier}`,
    width: 'full',
    children: [container({ cls: 'tc-wrap', inner: true, children })],
  });

const badge = (label) => heading(label, { tag: 'span', cls: 'tc-badge' });
const lead = (body) => text(`<p>${body}</p>`, { cls: 'tc-lead' });
const para = (body) => text(`<p>${body}</p>`);

const list = (items, cls) =>
  text(`<ul class="${cls}">${items.map((i) => `<li>${i}</li>`).join('')}</ul>`, { cls });

const buyButton = (label, cls = 'tc-btn tc-btn--primary') =>
  button(label, c.checkout.url, { cls, newTab: true });

// ---------------------------------------------------------------- sections

function hero() {
  const copy = container({
    cls: 'tc-hero__copy',
    inner: true,
    children: [
      badge(c.hero.badge),
      heading(c.hero.h1, { tag: 'h1', cls: 'tc-h1' }),
      lead(c.hero.sub),
      list(c.hero.checks, 'tc-checks'),
      buyButton(c.hero.cta),
      text(`<p>${c.hero.ctaNote}</p>`, { cls: 'tc-note' }),
    ],
  });

  const art = container({
    cls: 'tc-hero__art',
    inner: true,
    // The hero image is this page's LCP element, so it must not be lazy-loaded.
    children: [image(PLACEHOLDER, c.hero.imageAlt, { cls: 'tc-hero__img', lazy: false })],
  });

  return section('hero', [container({ cls: 'tc-hero', inner: true, children: [copy, art] })]);
}

function benefits() {
  const cards = c.benefits.cards.map((card) =>
    container({
      cls: `tc-card tc-card--icon tc-icon-${card.icon}`,
      inner: true,
      children: [heading(card.title, { tag: 'h3', cls: 'tc-h3' }), para(card.text)],
    }),
  );

  return section('benefits', [
    badge(c.benefits.badge),
    heading(c.benefits.h2, { tag: 'h2', cls: 'tc-h2' }),
    lead(c.benefits.intro),
    container({ cls: 'tc-grid tc-grid--3', inner: true, children: cards }),
  ]);
}

function curriculum() {
  const weeks = c.curriculum.weeks.map((w) =>
    container({
      cls: 'tc-week',
      inner: true,
      children: [
        text(
          `<p class="tc-week__meta"><strong>${esc(w.label)}</strong> <span>${esc(w.days)}</span></p>`,
          { cls: 'tc-week__meta-wrap' },
        ),
        heading(w.title, { tag: 'h3', cls: 'tc-h3' }),
        para(w.text),
      ],
    }),
  );

  const bonusItems = c.curriculum.bonus
    .map((b) => `<li><strong>${esc(b.title)}</strong><span>${esc(b.text)}</span></li>`)
    .join('');

  return section('curriculum', [
    badge(c.curriculum.badge),
    heading(c.curriculum.h2, { tag: 'h2', cls: 'tc-h2' }),
    lead(c.curriculum.intro),
    container({ cls: 'tc-weeks', inner: true, children: weeks }),
    heading(c.curriculum.bonusTitle, { tag: 'h3', cls: 'tc-h3 tc-h3--center' }),
    text(`<ul class="tc-bonus">${bonusItems}</ul>`, { cls: 'tc-bonus-wrap' }),
  ]);
}

function instructor() {
  const photo = container({
    cls: 'tc-bio__art',
    inner: true,
    children: [image(PLACEHOLDER, c.instructor.imageAlt, { cls: 'tc-bio__img' })],
  });

  const copy = container({
    cls: 'tc-bio__copy',
    inner: true,
    children: [
      badge(c.instructor.badge),
      heading(c.instructor.h2, { tag: 'h2', cls: 'tc-h2' }),
      text(`<p>${c.instructor.role}</p>`, { cls: 'tc-role' }),
      text(c.instructor.paragraphs.map((p) => `<p>${p}</p>`).join('')),
      list(c.instructor.credentials, 'tc-checks'),
    ],
  });

  return section('bio', [container({ cls: 'tc-bio', inner: true, children: [photo, copy] })]);
}

function testimonials() {
  const cards = c.testimonials.items.map((t) =>
    container({
      cls: 'tc-quote',
      inner: true,
      children: [
        starRating(t.stars, { cls: 'tc-stars' }),
        text(`<blockquote><p>${t.quote}</p></blockquote>`, { cls: 'tc-quote__body' }),
        text(
          `<p class="tc-quote__who"><strong>${esc(t.name)}</strong><span>${esc(t.role)}</span></p>`,
          { cls: 'tc-quote__who-wrap' },
        ),
      ],
    }),
  );

  return section('quotes', [
    badge(c.testimonials.badge),
    heading(c.testimonials.h2, { tag: 'h2', cls: 'tc-h2' }),
    text(`<p>${c.testimonials.disclaimer}</p>`, { cls: 'tc-disclaimer' }),
    container({ cls: 'tc-grid tc-grid--2', inner: true, children: cards }),
  ]);
}

function guarantee() {
  return section('guarantee', [
    container({
      cls: 'tc-guarantee',
      inner: true,
      children: [
        text(`<p class="tc-seal">${esc(c.guarantee.seal)}</p>`, { cls: 'tc-seal-wrap' }),
        container({
          cls: 'tc-guarantee__copy',
          inner: true,
          children: [
            badge(c.guarantee.badge),
            heading(c.guarantee.h2, { tag: 'h2', cls: 'tc-h2' }),
            para(c.guarantee.text),
          ],
        }),
      ],
    }),
  ]);
}

function offer() {
  const rows = c.offer.items
    .map((i) => `<li><span>${esc(i.label)}</span><strong>${esc(i.value)}</strong></li>`)
    .join('');

  const totalRow =
    `<li class="tc-pricelist__total"><span>${esc(c.offer.totalLabel)}</span>` +
    `<strong>${esc(c.offer.totalValue)}</strong></li>`;

  return section('offer', [
    badge(c.offer.badge),
    heading(c.offer.h2, { tag: 'h2', cls: 'tc-h2' }),
    text(`<ul class="tc-pricelist">${rows}${totalRow}</ul>`, { cls: 'tc-pricelist-wrap' }),
    text(
      `<p class="tc-price"><span>${esc(c.offer.priceLabel)}</span>` +
        `<strong>${esc(c.checkout.price)}</strong>` +
        `<em>${esc(c.checkout.installments)}</em></p>`,
      { cls: 'tc-price-wrap' },
    ),
    buyButton(c.offer.cta, 'tc-btn tc-btn--primary tc-btn--lg'),
    list(c.offer.reinforcements, 'tc-checks tc-checks--light'),
    text(`<p>${c.offer.scarcity}</p>`, { cls: 'tc-note tc-note--light' }),
  ]);
}

function faq() {
  return section('faq', [
    badge(c.faq.badge),
    heading(c.faq.h2, { tag: 'h2', cls: 'tc-h2' }),
    accordion(c.faq.items, { cls: 'tc-faq' }),
  ]);
}

function closing() {
  return section('closing', [
    heading(c.closing.h2, { tag: 'h2', cls: 'tc-h2' }),
    lead(c.closing.text),
    buyButton(c.closing.cta, 'tc-btn tc-btn--primary tc-btn--lg'),
    button(c.closing.ctaSecondary, waLink, { cls: 'tc-btn tc-btn--ghost', newTab: true }),
  ]);
}

function footer() {
  const links = c.footer.links.map((l) => `<a href="#">${esc(l)}</a>`).join(' <i>&middot;</i> ');

  return section('footer', [
    text(`<p>${c.footer.demoNotice}</p>`, { cls: 'tc-demo-notice' }),
    text(`<p class="tc-footer__links">${links}</p>`, { cls: 'tc-footer__links-wrap' }),
    text(`<p>${c.footer.credit}</p>`, { cls: 'tc-credit' }),
    text(`<p>${c.footer.legal}</p>`, { cls: 'tc-legal' }),
  ]);
}

// ---------------------------------------------------------------- assemble

resetIds();

const page = template(`Landing - ${c.meta.title}`, [
  hero(), benefits(), curriculum(), instructor(), testimonials(),
  guarantee(), offer(), faq(), closing(), footer(),
]);

// ---------------------------------------------------------------- validate

function validate(tpl) {
  const problems = [];
  const seen = new Set();
  let widgets = 0;
  let containers = 0;

  const walk = (node, path) => {
    if (!node.id) problems.push(`${path}: missing id`);
    if (seen.has(node.id)) problems.push(`${path}: duplicate id ${node.id}`);
    seen.add(node.id);

    if (node.elType === 'widget') {
      widgets += 1;
      if (!node.widgetType) problems.push(`${path}: widget without widgetType`);
      if (!node.settings || typeof node.settings !== 'object') {
        problems.push(`${path}: widget without settings`);
      }
    } else if (node.elType === 'container') {
      containers += 1;
      if (!Array.isArray(node.elements)) problems.push(`${path}: container without elements[]`);
    } else {
      problems.push(`${path}: unknown elType ${node.elType}`);
    }

    (node.elements || []).forEach((child, i) => walk(child, `${path}>${node.elType}[${i}]`));
  };

  tpl.content.forEach((node, i) => walk(node, `content[${i}]`));

  // The importer rejects a file missing any of these keys.
  for (const key of ['content', 'page_settings', 'version', 'title', 'type']) {
    if (!(key in tpl)) problems.push(`template: missing top-level key ${key}`);
  }

  return { problems, widgets, containers, total: seen.size };
}

const report = validate(page);
const out = join(ROOT, 'elementor', 'landing-treino-30-dias.json');
const json = JSON.stringify(page, null, 2);

if (report.problems.length > 0) {
  console.error('INVALID template -- nothing written:\n');
  report.problems.forEach((p) => console.error('  - ' + p));
  process.exit(1);
}

writeFileSync(out, json, 'utf8');

console.log('Elementor template written');
console.log('  file       : elementor/landing-treino-30-dias.json');
console.log(`  sections   : ${page.content.length}`);
console.log(`  containers : ${report.containers}`);
console.log(`  widgets    : ${report.widgets}`);
console.log(`  elements   : ${report.total} (all ids unique)`);
console.log(`  size       : ${(Buffer.byteLength(json, 'utf8') / 1024).toFixed(1)} KB`);
