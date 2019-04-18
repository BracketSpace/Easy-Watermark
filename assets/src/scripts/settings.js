import $ from 'jquery'

class Settings {
	constructor() {
		$( document ).ready( () => {
			this.init()
		} )
	}

	init() {
		this.form = $( 'form#easy-watermark-settings-form' )

		if ( ! this.form.length ) {
			return
		}

		this.toggleBackupSettingsVisibility = this.toggleBackupSettingsVisibility.bind( this )

		this.backupCheckbox = this.form.find( '#ew-backup' )
		this.backupSettings = this.form.find( '#backup-settings' )
		this.backupFields   = this.backupSettings.find( 'input, select' )

		this.backupCheckbox.on( 'change', this.toggleBackupSettingsVisibility )

		this.toggleBackupSettingsVisibility()
	}

	toggleBackupSettingsVisibility() {

		if (  true === this.backupCheckbox.is( ':checked' ) ) {
			this.backupSettings.show()
			this.backupFields.prop( 'disabled', false )
		} else {
			this.backupSettings.hide()
			this.backupFields.prop( 'disabled', true )
		}
	}
}

new Settings()
