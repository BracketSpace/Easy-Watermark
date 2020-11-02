/**
 * @param state
 * @param feature
 */
export function isFeatureActive( state, feature ) {
	return true === state.features[ feature ];
}
