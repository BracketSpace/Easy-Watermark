/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { withSelect } from '@wordpress/data';
import {
	Panel,
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import SidebarHeader from './header';
import WatermarkName from './watermark-name';
import ImageSizes from './image-sizes';
import ImageTypes from './image-types';
import PostTypes from './post-types';
import AutoWatermark from './auto-watermark';

const GeneralSidebar = () => (
	<Panel>
		<WatermarkName />
		<AutoWatermark />
		<ImageTypes />
		<PostTypes />
		<ImageSizes />
	</Panel>
);

const ObjectSidebar = () => (
	<Panel>
		<p>Object Panel</p>
	</Panel>
);

/**
 *
 */
function Sidebar( { isSidebarOpened, activeTab } ) {
	if ( ! isSidebarOpened ) {
		return null;
	}

	const Content = ( 'object' === activeTab ) ? ObjectSidebar : GeneralSidebar;

	return (
		<div
			className="interface-complementary-area edit-post-sidebar"
			role="region"
			aria-label={ __( 'Settings Sidebar' ) }
			tabIndex="-1"
		>
			<SidebarHeader />
			<Content />
		</div>
	);
}

export default withSelect( ( select ) => {
	const {
		isSidebarOpened,
		getActiveSidebarTab,
	} = select( 'easy-watermark' );

	return {
		isSidebarOpened: isSidebarOpened(),
		activeTab: getActiveSidebarTab(),
	};
} )( Sidebar );
