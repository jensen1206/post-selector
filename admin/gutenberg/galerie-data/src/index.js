//  Import CSS.
import './editor.scss';
import './style.scss';

const {Component} = wp.element;
import {SelectGalerie} from './components/galerieTypes';
import {
    InspectorControls,
    ColorPaletteControl
} from '@wordpress/block-editor';
import {__} from '@wordpress/i18n';
// eslint-disable-next-line no-unused-vars
const {registerBlockType, PlainText} = wp.blocks;
import {
    TextControl,
    ToggleControl,
    PanelBody,
    Panel,
    RadioControl

} from '@wordpress/components';

registerBlockType('hupa/post-selector-galerie', {
    title: __('Post Selector Galerie'),
    icon: 'format-gallery',
    category: 'media',
    attributes: {
        selectedGalerie: {
            type: 'string',
        },
        valWidgetInput: {
            type: 'string',
        },
        hoverBGColor: {
            type: 'string',
            default: ''
        },
        TextColor: {
            type: 'string',
            default: ''
        },
    },
    keywords: [
        __(' Gutenberg Galerie BY Jens Wiecker'),
        __('Gutenberg POST Selector Galerie'),
    ],

    edit: class extends Component {
        constructor(props) {
            super(...arguments);
            this.props = props;
            this.updateSelectedGalerie = this.updateSelectedGalerie.bind(this);
            this.onOverWidgetInputChange = this.onOverWidgetInputChange.bind(this);

            this.onChangeBGColor = this.onChangeBGColor.bind(this);
            this.onChangeTextColor = this.onChangeTextColor.bind(this);
        }

        updateSelectedGalerie(selectedGalerie) {
            this.props.setAttributes({selectedGalerie});
        }

        onOverWidgetInputChange(valWidgetInput) {
            this.props.setAttributes({valWidgetInput});
        }

        onChangeBGColor(hoverBGColor) {
            this.props.setAttributes({hoverBGColor});
        }

        onChangeTextColor(TextColor) {
            this.props.setAttributes({TextColor});
        }

        render() {

            const SmallLine = ({color}) => (
                <hr
                    className="hr-small-trenner"
                />
            );
            const {overWidgetInput, attributes: {valWidgetInput = ''} = {}} = this.props;
            const {inputBGColor, attributes: {hoverBGColor = ''} = {}} = this.props;
            const {inputTextColor, attributes: {TextColor = ''} = {}} = this.props;
            return (
                <div className="wp-block-hupa-post-selector-galerie">
                    <InspectorControls>
                        <div id="hupa-posts-controls">
                            <Panel>
                                <PanelBody
                                    className="hupa-body-sidebar"
                                    title="Settings"
                                    initialOpen={true}
                                >
                                    <TextControl className= {overWidgetInput}
                                                 label="Überschrift für Widget:"
                                                 value={valWidgetInput}
                                                 onChange={this.onOverWidgetInputChange}
                                                 type="text"
                                                 help="Nur für Gutenberg Widgets relevant."

                                    />

                                </PanelBody>
                            </Panel>
                            <Panel>
                                <PanelBody
                                    className="hupa-body-sidebar"
                                    title="Farben für Hover-Box"
                                    initialOpen={false}
                                >
                                    <div className="sidebar-input-headline">
                                        Hover Textfarbe
                                    </div>
                                    <div className={inputTextColor}>
                                        <ColorPaletteControl
                                            onChange={this.onChangeTextColor}
                                            value={TextColor}
                                        />
                                    </div>

                                    <div className="sidebar-input-headline">
                                        {__('Hover Hintergrundfarbe', 'wp-post-selector')}
                                    </div>
                                    <div className={inputBGColor}>
                                        <ColorPaletteControl
                                            onChange={this.onChangeBGColor}
                                            value={hoverBGColor}
                                        />
                                    </div>
                                </PanelBody>
                            </Panel>
                        </div>
                    </InspectorControls>
                    <Panel className="galerie-form-panel">
                        <h5 className="galerie-headline">Post-Selector Galerie </h5>
                        <SmallLine/>
                        <SelectGalerie
                            /* TODO JOB select Galerie */
                            selectedGalerie={this.props.attributes.selectedGalerie}
                            updateSelectedGalerie={this.updateSelectedGalerie}
                        />
                    </Panel>

                </div>
            );
        }
    },
    save() {
        return null;
    },
});