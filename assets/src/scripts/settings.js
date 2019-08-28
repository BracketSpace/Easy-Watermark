/**
 * External dependencies
 */
import $ from 'jquery';

/**
 * Internal dependencies
 */
import '../styles/components/_switch.scss';

class Settings {
	constructor() {
		this.form = $( 'form#easy-watermark-settings-form' );

		if ( this.form.length ) {
			this.init();
		}
	}

	init() {
		this.toggleBackupSettingsVisibility = this.toggleBackupSettingsVisibility.bind( this );

		this.backupCheckbox = this.form.find( '#ew-backup' );
		this.backupSettings = this.form.find( '#backup-settings' );
		this.backupFields = this.backupSettings.find( 'input, select' );

		this.backupCheckbox.on( 'change', this.toggleBackupSettingsVisibility );

		this.toggleBackupSettingsVisibility();
	}

	toggleBackupSettingsVisibility() {
		if ( true === this.backupCheckbox.is( ':checked' ) ) {
			this.backupSettings.show();
			this.backupFields.prop( 'disabled', false );
		} else {
			this.backupSettings.hide();
			this.backupFields.prop( 'disabled', true );
		}
	}
}

$( document ).ready( () => new Settings );
