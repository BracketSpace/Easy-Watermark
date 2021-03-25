/**
 * Determine if given feature is active
 *
 * @param  {Object}  state   State object.
 * @param  {string}  feature Feature name.
 * @return {boolean}         Whether the feature is active.
 */
export function isFeatureActive( state, feature ) {
	return true === state.features[ feature ];
}
