/**
 * Internal dependencies
 */
import Tool from './tool';

export default class extends Tool {
	constructor( options ) {
		options.el = '.tool-restore';

		super( options );

		this.action = 'restore';

		this.backupInfo = this.$el.find( 'p.has-backup' );
		this.noBackupInfo = this.$el.find( 'p.no-backup' );
		this.backupCountInfo = this.$el.find( '.backup-count' );

		this.state.set( {
			backupCount: this.$el.data( 'backup-count' ),
		} );

		this.toggleInfo();
	}

	handleClick() {
		this.state.set( {
			nonce: this.button.data( 'nonce' ),
		} );

		super.handleClick();
	}

	toggleInfo() {
		const backupCount = this.state.get( 'backupCount' );

		if ( 0 < backupCount ) {
			this.backupCountInfo.text( backupCount );
			this.backupInfo.show();
			this.noBackupInfo.hide();
		} else {
			this.backupInfo.hide();
			this.noBackupInfo.show();
		}
	}

	update() {
		super.update();
		this.toggleInfo();
	}
}
