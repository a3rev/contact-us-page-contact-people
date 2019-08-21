/*
 * Inspector Settings
 */
import InspectorGlobalSettings from './components/inspector/global-settings';

import InspectorCardSpacing, { cardSpacingAttributes } from './components/inspector/card-spacing';

const { Component } = wp.element;
const { InspectorControls } = wp.blockEditor;

export { cardSpacingAttributes };

/**
 * Inspector controls
 */
export default class Inspector extends Component {
	render() {
		return (
			<InspectorControls>
				<InspectorGlobalSettings { ...this.props } />
				<InspectorCardSpacing { ...this.props } />
			</InspectorControls>
		);
	}
}
