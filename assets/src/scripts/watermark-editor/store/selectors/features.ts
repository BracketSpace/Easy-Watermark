/**
 * Internal dependencies
 */
import { TStoreState } from 'types';

/**
 * Determine if given feature is active
 *
 * @param  state   State object.
 * @param  feature Feature name.
 * @return         Whether the feature is active.
 */
export function isFeatureActive( state: TStoreState, feature: string ) : boolean {
	return true === state.features[ feature ];
}
