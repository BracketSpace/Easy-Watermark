/**
 * External dependencies
 */
import { View } from 'backbone';

export default class extends View {
	constructor( options ) {
		super( options );

		this.controller = options.controller;
		this.bulkActionSelector = options.bulkActionSelector;
	}
}
