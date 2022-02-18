<?php
namespace Post\Selector;

use Post_Selector;

class Post_Selector_Database_Handle {

	/**
	 * The current version of the DB-Version.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $db_version The current version of the database Version.
	 */
	protected string $db_version;


	use Post_Selector_Defaults;

	/**
	 * Store plugin main class to allow public access.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var Post_Selector $main The main class.
	 */
	private Post_Selector $main;

	public function __construct( string $db_version,  Post_Selector $main ) {

		$this->db_version   = $db_version;
		$this->main       = $main;

	}

	public function post_selector_check_jal_install() {
		if ( get_option( 'jal_post_selector_two_db_version' ) != $this->db_version ) {
			 update_option('jal_post_selector_two_db_version', $this->db_version);
			$this->post_selector_jal_install();
		}
	}

	public function post_selector_jal_install() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		global $wpdb;
		$table_name      = $wpdb->prefix . $this->table_slider;
		$charset_collate = $wpdb->get_charset_collate();
		$sql             = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        slider_id varchar(14) NOT NULL UNIQUE,
        bezeichnung varchar(128) NOT NULL,
        data text NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
       PRIMARY KEY (id)
     ) $charset_collate;";
		dbDelta( $sql );

		$table_name      = $wpdb->prefix . $this->table_galerie;
		$charset_collate = $wpdb->get_charset_collate();
		$sql             = "CREATE TABLE $table_name (
        id int(11) NOT NULL AUTO_INCREMENT,
        bezeichnung varchar(60) NOT NULL,
        beschreibung text,
        type mediumint(6) NOT NULL,
        type_settings text NOT NULL,
        link varchar(255) NULL,
        is_link  BOOLEAN NULL,
        hover_aktiv  BOOLEAN NOT NULL DEFAULT FALSE,
        link_target  BOOLEAN NOT NULL DEFAULT TRUE,
        hover_title_aktiv  BOOLEAN NOT NULL DEFAULT TRUE,
        lazy_load_aktiv BOOLEAN NOT NULL DEFAULT TRUE,
        lazy_load_ani_aktiv BOOLEAN NOT NULL DEFAULT TRUE,
        animate_select varchar(60) NULL,
        hover_beschreibung_aktiv  BOOLEAN NOT NULL DEFAULT TRUE,  
        lightbox_aktiv  BOOLEAN NOT NULL DEFAULT TRUE,
        caption_aktiv  BOOLEAN NOT NULL DEFAULT TRUE, 
        show_bezeichnung  BOOLEAN NOT NULL DEFAULT FALSE,
        show_beschreibung  BOOLEAN NOT NULL DEFAULT FALSE,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
       PRIMARY KEY (id)
     ) $charset_collate;";
		dbDelta( $sql );

		$table_name      = $wpdb->prefix . $this->table_galerie_images;
		$charset_collate = $wpdb->get_charset_collate();
		$sql             = "CREATE TABLE $table_name ( 
        id int(11) NOT NULL AUTO_INCREMENT,
        galerie_id mediumint(11) NOT NULL,
        img_id int(11) NOT NULL,
        position int(11) NOT NULL DEFAULT 0,
        img_caption varchar(128) NULL,
        img_title varchar(128) NULL, 
        img_beschreibung text NULL,
        link varchar(255) NULL,
        is_link  BOOLEAN NULL,
        galerie_settings_aktiv BOOLEAN NOT NULL DEFAULT TRUE,
        hover_aktiv BOOLEAN NOT NULL DEFAULT FALSE,
        link_target  BOOLEAN NOT NULL DEFAULT TRUE,
        hover_title_aktiv BOOLEAN NOT NULL DEFAULT FALSE,
        hover_beschreibung_aktiv BOOLEAN NOT NULL DEFAULT FALSE,      
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
       PRIMARY KEY (id)
     ) $charset_collate;";
		dbDelta( $sql );
	}
}