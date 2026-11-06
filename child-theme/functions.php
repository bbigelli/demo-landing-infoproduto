<?php
/**
 * Child theme "Treino em Casa 30 Dias".
 *
 * Responsabilidades deste arquivo:
 *  - carregar o CSS do tema pai e do filho;
 *  - injetar o botão flutuante de WhatsApp e a barra fixa de oferta;
 *  - instalar o Pixel da Meta e disparar os eventos de conversão;
 *  - remover do caminho crítico o que o WordPress e o Elementor carregam
 *    por padrão e esta página não usa.
 *
 * O visual vive em style.css. A estrutura e os textos da página vivem no
 * template do Elementor (elementor/landing-treino-30-dias.json).
 *
 * @package treino-casa-30
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * Configuração
 *
 * O ID do Pixel NÃO fica no repositório. Defina em wp-config.php:
 *
 *     define( 'TC_META_PIXEL_ID', '000000000000000' );
 *
 * Sem a constante definida, nenhum script de rastreamento é impresso --
 * é o comportamento correto para um repositório público e para ambiente
 * local, onde disparar evento de Pixel só suja os dados da conta.
 * ---------------------------------------------------------------------- */

/**
 * Configuração da página, filtrável.
 *
 * @return array<string,string>
 */
function tc_config() {
	return apply_filters(
		'tc_config',
		array(
			// Trocar pelo número real antes de publicar. Formato: 55 + DDD + número.
			'whatsapp_phone'   => '5511999999999',
			'whatsapp_message' => 'Olá! Vi a página do Treino em Casa 30 Dias e queria tirar uma dúvida.',
			'whatsapp_label'   => 'Falar no WhatsApp',
			'whatsapp_aria'    => 'Abrir conversa no WhatsApp',
			'checkout_url'     => 'https://pay.hotmart.com/EXEMPLO-DEMO',
			'sticky_text'      => 'Oferta desta turma: R$ 197',
			'sticky_cta'       => 'Garantir',
			// URL da imagem do hero, para preload. Atualizar após subir a imagem real.
			'hero_image'       => '',
		)
	);
}

/**
 * Monta o link wa.me com a mensagem já preenchida.
 *
 * @return string
 */
function tc_whatsapp_url() {
	$cfg = tc_config();

	return sprintf(
		'https://wa.me/%s?text=%s',
		rawurlencode( $cfg['whatsapp_phone'] ),
		rawurlencode( $cfg['whatsapp_message'] )
	);
}

/**
 * Esta é uma landing de página única: as customizações abaixo só devem
 * valer na home, para não afetar wp-admin nem outras páginas do site.
 *
 * @return bool
 */
function tc_is_landing() {
	return is_front_page() || is_page( 'treino-em-casa-30-dias' );
}

/* -------------------------------------------------------------------------
 * Estilos
 * ---------------------------------------------------------------------- */

add_action( 'wp_enqueue_scripts', 'tc_enqueue_styles', 20 );
/**
 * Carrega o CSS do tema pai e do filho.
 *
 * @return void
 */
function tc_enqueue_styles() {
	$theme = wp_get_theme();

	wp_enqueue_style(
		'hello-elementor-child',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'hello-elementor' ),
		$theme->get( 'Version' )
	);
}

/* -------------------------------------------------------------------------
 * Performance
 *
 * Cada item abaixo foi removido porque esta página não usa o recurso.
 * Nenhum deles é "otimização agressiva": são arquivos que o WordPress
 * enfileira por padrão para casos que aqui não existem.
 * ---------------------------------------------------------------------- */

add_action( 'init', 'tc_trim_default_assets' );
/**
 * Remove do caminho crítico o que esta página não usa.
 *
 * @return void
 */
function tc_trim_default_assets() {
	// Script de emoji: ~12KB de JS mais uma requisição a s.w.org, para
	// converter emoji em imagem em navegadores que ninguém mais usa.
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );

	// Links de descoberta que uma landing page não precisa expor.
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
}

add_action( 'wp_enqueue_scripts', 'tc_dequeue_unused_assets', 100 );
/**
 * Retira estilos e scripts enfileirados que esta página não utiliza.
 *
 * Roda em prioridade 100 para acontecer depois do Elementor enfileirar
 * os dele.
 *
 * @return void
 */
function tc_dequeue_unused_assets() {
	if ( is_admin() || ! tc_is_landing() ) {
		return;
	}

	// CSS do editor de blocos: a página é montada no Elementor, não no
	// Gutenberg. São cerca de 28KB que nenhum elemento daqui usa.
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );

	// wp-embed só serve para incorporar posts de outros sites WordPress.
	wp_dequeue_script( 'wp-embed' );
	wp_deregister_script( 'wp-embed' );

	// Font Awesome. O template foi construído sem nenhum widget de ícone:
	// os check e os ícones dos cards são SVG inline em background-image,
	// definidos em style.css. Carregar a biblioteca inteira para zero
	// ícones custa cerca de 70KB no caminho crítico.
	wp_dequeue_style( 'font-awesome' );
	wp_dequeue_style( 'elementor-icons-shared-0' );
	wp_dequeue_style( 'elementor-icons-fa-solid' );
	wp_dequeue_style( 'elementor-icons-fa-regular' );
	wp_dequeue_style( 'elementor-icons-fa-brands' );
}

// Shim de compatibilidade do Font Awesome 4, desnecessário em instalação nova.
add_filter( 'elementor_pro_font_awesome_4_shim', '__return_empty_array' );
add_action(
	'elementor/frontend/after_register_styles',
	function () {
		wp_deregister_style( 'font-awesome-4-shim' );
	},
	20
);

// Impede o Elementor de chamar fonts.googleapis.com. A Inter é servida
// localmente por este tema (ver o bloco @font-face em style.css), o que
// elimina duas conexões externas do caminho crítico e resolve a
// recomendação "Reduza o tempo de resposta inicial do servidor".
add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );

add_action( 'wp_head', 'tc_resource_hints', 1 );
/**
 * Preload das fontes locais e da imagem do hero.
 *
 * A fonte é descoberta pelo navegador só depois de baixar e processar o
 * CSS; o preload antecipa isso e evita o lampejo de texto trocando de
 * fonte. O hero é o elemento de LCP desta página -- sem o preload, ele só
 * começa a baixar depois do CSS, e o LCP atrasa junto.
 *
 * @return void
 */
function tc_resource_hints() {
	if ( ! tc_is_landing() ) {
		return;
	}

	$fonts = array( 'inter-400.woff2', 'inter-700.woff2' );
	$dir   = get_stylesheet_directory();
	$uri   = get_stylesheet_directory_uri();

	foreach ( $fonts as $font ) {
		// Só anuncia o preload se o arquivo existir: preload de recurso
		// ausente gera um 404 e um aviso no console.
		if ( ! file_exists( $dir . '/fonts/' . $font ) ) {
			continue;
		}

		printf(
			'<link rel="preload" as="font" type="font/woff2" href="%s" crossorigin>' . "\n",
			esc_url( $uri . '/fonts/' . $font )
		);
	}

	$hero = tc_config()['hero_image'];

	if ( $hero ) {
		printf(
			'<link rel="preload" as="image" href="%s" fetchpriority="high">' . "\n",
			esc_url( $hero )
		);
	}
}

/* -------------------------------------------------------------------------
 * Pixel da Meta
 *
 * Três pontos de configuração, nesta ordem:
 *
 *  1. ID do Pixel        -> constante TC_META_PIXEL_ID em wp-config.php
 *  2. Código base        -> tc_meta_pixel_base(), impresso em wp_head
 *  3. Eventos de conversão -> tc_meta_pixel_events(), em wp_footer
 *
 * PageView e ViewContent disparam no carregamento. InitiateCheckout
 * dispara no clique de qualquer botão que aponte para o checkout, o que
 * cobre os três CTAs da página sem precisar marcar cada um.
 *
 * Para validar: extensão Meta Pixel Helper no Chrome, ou
 * Gerenciador de Eventos > Testar eventos.
 * ---------------------------------------------------------------------- */

/**
 * Retorna o ID do Pixel, se configurado.
 *
 * @return string
 */
function tc_pixel_id() {
	return defined( 'TC_META_PIXEL_ID' ) ? (string) TC_META_PIXEL_ID : '';
}

add_action( 'wp_head', 'tc_meta_pixel_base', 5 );
/**
 * Código base do Pixel.
 *
 * @return void
 */
function tc_meta_pixel_base() {
	$pixel_id = tc_pixel_id();

	if ( ! $pixel_id || ! tc_is_landing() ) {
		return;
	}
	?>
<!-- Meta Pixel -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', <?php echo wp_json_encode( $pixel_id ); ?>);
fbq('track', 'PageView');
fbq('track', 'ViewContent', {
  content_name: 'Treino em Casa 30 Dias',
  content_type: 'product',
  value: 197.00,
  currency: 'BRL'
});
</script>
<noscript><img height="1" width="1" style="display:none" alt=""
  src="https://www.facebook.com/tr?id=<?php echo esc_attr( $pixel_id ); ?>&ev=PageView&noscript=1"></noscript>
<!-- /Meta Pixel -->
	<?php
}

add_action( 'wp_footer', 'tc_meta_pixel_events', 30 );
/**
 * Dispara InitiateCheckout no clique de qualquer CTA de compra, e Contact
 * no clique do WhatsApp.
 *
 * @return void
 */
function tc_meta_pixel_events() {
	if ( ! tc_pixel_id() || ! tc_is_landing() ) {
		return;
	}
	?>
<script>
(function () {
  if (typeof window.fbq !== 'function') return;

  document.addEventListener('click', function (event) {
    var link = event.target.closest('a[href]');
    if (!link) return;

    if (link.href.indexOf('pay.hotmart.com') !== -1) {
      window.fbq('track', 'InitiateCheckout', {
        content_name: 'Treino em Casa 30 Dias',
        value: 197.00,
        currency: 'BRL'
      });
    } else if (link.href.indexOf('wa.me') !== -1) {
      window.fbq('track', 'Contact');
    }
  }, { passive: true });
})();
</script>
	<?php
}

/* -------------------------------------------------------------------------
 * Botão flutuante de WhatsApp e barra fixa de oferta
 * ---------------------------------------------------------------------- */

add_action( 'wp_footer', 'tc_whatsapp_button', 20 );
/**
 * Botão flutuante de WhatsApp.
 *
 * O ícone é SVG inline: é um arquivo a menos para baixar e não depende de
 * biblioteca de ícones, que foi retirada da página de propósito.
 *
 * @return void
 */
function tc_whatsapp_button() {
	if ( ! tc_is_landing() ) {
		return;
	}

	$cfg = tc_config();
	?>
<a class="tc-wa" href="<?php echo esc_url( tc_whatsapp_url() ); ?>"
   target="_blank" rel="noopener noreferrer"
   aria-label="<?php echo esc_attr( $cfg['whatsapp_aria'] ); ?>">
	<svg class="tc-wa__icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
		<path d="M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.92 1.17-.15.2-.3.22-.6.07-.3-.15-1.27-.47-2.42-1.49-.9-.8-1.5-1.79-1.67-2.09-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.18.2-.3.3-.5.1-.2.05-.38-.02-.53-.08-.15-.68-1.62-.93-2.21-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.48 0 1.47 1.07 2.89 1.22 3.09.15.2 2.1 3.2 5.08 4.49.71.3 1.27.49 1.7.63.72.23 1.37.2 1.89.12.58-.09 1.78-.73 2.03-1.43.25-.7.25-1.3.17-1.43-.07-.12-.27-.2-.57-.35z"/>
		<path d="M12.04 2C6.6 2 2.17 6.43 2.17 11.87c0 1.74.46 3.44 1.32 4.94L2 22l5.33-1.4a9.84 9.84 0 0 0 4.7 1.2h.01c5.44 0 9.87-4.43 9.87-9.87 0-2.64-1.03-5.12-2.9-6.99A9.8 9.8 0 0 0 12.04 2zm0 18.06h-.01a8.2 8.2 0 0 1-4.17-1.14l-.3-.18-3.1.81.83-3.03-.2-.31a8.17 8.17 0 0 1-1.25-4.34c0-4.52 3.68-8.2 8.2-8.2a8.15 8.15 0 0 1 8.2 8.21c0 4.52-3.68 8.18-8.2 8.18z"/>
	</svg>
	<span class="tc-wa__label"><?php echo esc_html( $cfg['whatsapp_label'] ); ?></span>
</a>
	<?php
}

add_action( 'wp_footer', 'tc_sticky_offer_bar', 21 );
/**
 * Barra fixa de oferta, visível apenas no celular.
 *
 * Fica no footer em PHP, e não como container do Elementor: elemento de
 * posição fixa dentro da árvore do Elementor quebra o cálculo de altura
 * das seções e aparece no editor atrapalhando a edição.
 *
 * @return void
 */
function tc_sticky_offer_bar() {
	if ( ! tc_is_landing() ) {
		return;
	}

	$cfg = tc_config();
	?>
<div class="tc-sticky" role="complementary" aria-label="Oferta">
	<span class="tc-sticky__text"><?php echo esc_html( $cfg['sticky_text'] ); ?></span>
	<a class="tc-sticky__cta" href="<?php echo esc_url( $cfg['checkout_url'] ); ?>"
	   target="_blank" rel="noopener noreferrer"><?php echo esc_html( $cfg['sticky_cta'] ); ?></a>
</div>
	<?php
}

/* -------------------------------------------------------------------------
 * Acessibilidade e SEO
 * ---------------------------------------------------------------------- */

add_action( 'wp_body_open', 'tc_skip_link' );
/**
 * Link de pular para o conteúdo, primeiro elemento focável da página.
 *
 * @return void
 */
function tc_skip_link() {
	if ( ! tc_is_landing() ) {
		return;
	}

	echo '<a class="tc-skip-link" href="#content">Pular para o conteúdo</a>';
}

add_filter( 'language_attributes', 'tc_force_locale' );
/**
 * Garante lang="pt-BR" no html, independente da configuração do site.
 *
 * Leitor de tela pronuncia o texto com as regras do idioma declarado: com
 * lang errado, a página inteira é lida com fonética de outra língua.
 *
 * @param string $output Atributos atuais.
 * @return string
 */
function tc_force_locale( $output ) {
	if ( ! tc_is_landing() ) {
		return $output;
	}

	return 'lang="pt-BR"';
}
