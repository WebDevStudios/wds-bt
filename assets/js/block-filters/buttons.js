import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	PanelRow,
	ButtonGroup,
	Button,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Renders the edit component for the button block.
 *
 * @param {Object}   props                       - The properties passed to the component.
 * @param {Object}   props.attributes            - The attributes of the button block.
 * @param {string}   props.attributes.buttonSize - The size of the button.
 * @param {Function} props.setAttributes         - The function to set the attributes of the button block.
 * @return {JSX.Element} The rendered edit component.
 */
function Edit({ attributes: { buttonSize }, setAttributes }) {
	const handleChange = (newSize) => {
		// Check if we are toggling the size off
		const size = buttonSize === newSize ? undefined : newSize;
		const buttonClass = buttonSize !== newSize ? 'has-size-' + newSize : '';

		// Update attributes.
		setAttributes({
			buttonSize: size,
			className: buttonClass,
		});
	};

	return (
		<InspectorControls>
			<PanelBody title={__('Button Sizes')}>
				<PanelRow>
					<ButtonGroup aria-label={__('Button Sizes')}>
						{['s', 'm', 'l', 'xl'].map((sizeValue) => {
							return (
								<Button
									key={sizeValue}
									size="medium"
									variant={
										buttonSize === sizeValue
											? 'primary'
											: undefined
									}
									onClick={() => handleChange(sizeValue)}
								>
									{sizeValue.toUpperCase()}
								</Button>
							);
						})}
					</ButtonGroup>
				</PanelRow>
			</PanelBody>
		</InspectorControls>
	);
}

addFilter(
	'blocks.registerBlockType',
	'wds-bt/button-sizes',
	(settings, name) => {
		if (name !== 'core/button') {
			return settings;
		}

		return {
			...settings,
			attributes: {
				...settings.attributes,
				buttonSize: {
					type: 'string',
					selector: 'a',
					default: '',
				},
			},
		};
	}
);

addFilter(
	'editor.BlockEdit',
	'wds-bt/button-sizes',
	createHigherOrderComponent((BlockEdit) => {
		return (props) => {
			if (props.name !== 'core/button') {
				return <BlockEdit {...props} />;
			}

			return (
				<>
					<BlockEdit {...props} />
					<Edit {...props} />
				</>
			);
		};
	})
);
