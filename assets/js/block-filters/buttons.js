import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	PanelRow,
	ButtonGroup,
	Button,
	ToggleControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import TokenList from '@wordpress/token-list';

function Edit({
	attributes: { buttonSize, hasArrow, className },
	setAttributes,
}) {
	const replaceActiveSize = (currentSize, newSize) => {
		const currentClasses = new TokenList(className);

		if (currentSize) {
			currentClasses.remove(`has-size-${currentSize}`);
		}
		if (newSize) {
			currentClasses.add(`has-size-${newSize}`);
		}

		return currentClasses.value;
	};

	const handleArrowChange = (newArrow) => {
		const buttonClasses = new TokenList(className);

		if (newArrow) {
			buttonClasses.add('has-arrow');
		} else {
			buttonClasses.remove('has-arrow');
		}

		setAttributes({
			hasArrow: newArrow,
			className: buttonClasses.value,
		});
	};

	const handleSizeChange = (newSize) => {
		const size = buttonSize === newSize ? undefined : newSize;
		const buttonClass = replaceActiveSize(buttonSize, size);

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
						{['xl', 'l', 'm', 's'].map((sizeValue) => (
							<Button
								key={sizeValue}
								size="medium"
								variant={
									buttonSize === sizeValue
										? 'primary'
										: undefined
								}
								onClick={() => handleSizeChange(sizeValue)}
							>
								{sizeValue.toUpperCase()}
							</Button>
						))}
					</ButtonGroup>
				</PanelRow>
			</PanelBody>
			<PanelBody title={__('Button Icon')}>
				<PanelRow>
					<ToggleControl
						__nextHasNoMarginBottom
						label="Arrow"
						help="Display an arrow icon on the button."
						checked={hasArrow}
						onChange={(newValue) => handleArrowChange(newValue)}
					/>
				</PanelRow>
			</PanelBody>
		</InspectorControls>
	);
}

// Add filters.
addFilter(
	'blocks.registerBlockType',
	'wdsbt/button-sizes',
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
				hasArrow: {
					type: 'boolean',
					default: false,
				},
			},
		};
	}
);

addFilter(
	'editor.BlockEdit',
	'wdsbt/button-sizes',
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
