/**
 * Internal dependencies
 */
import { SAVE, TOGGLE_SAVING } from '../action-types';
import { save, toggleSaving } from './save';

describe('save actions', () => {
	it("should create action to save editor state", () => {
		expect(save()).toEqual({
			type: SAVE,
		});
	});

	it("should create action to toggle saving state", () => {
		expect(toggleSaving()).toEqual({
			type: TOGGLE_SAVING,
		});
	});
});
