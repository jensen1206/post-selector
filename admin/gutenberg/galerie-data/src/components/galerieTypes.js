/**
 * Gutenberg POST SELECTOR
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */

const {Component} = wp.element;
import axios from 'axios';

export class SelectGalerie extends Component {
    constructor(props) {
        super(...arguments);
        this.props = props;
        this.state = {
            selectGalerie: [],
        }
        this.galerieSelectChange = this.galerieSelectChange.bind(this);
    }

    componentDidMount() {
        axios.get(WPPSRestObj.url + 'get_galerie_data', {
            headers: {
                'content-type': 'application/json',
                'X-WP-Nonce': WPPSRestObj.nonce
            }
        })
            .then(({data = {}} = {}) => {
                this.setState({
                    selectGalerie: data.select,

                });
            });
    }

    galerieSelectChange(e) {
        this.props.updateSelectedGalerie(
            this.props.selectedGalerie = e
        );
    }

    render() {
        return (
            <div>
                <div className="settings-form-flex-column">
                    <label className="form-label" htmlFor="GalerieSelect"><b className="b-fett">Galerie</b> auswählen: </label>
                    <select className="form-select" name="options" id="GalerieSelect"
                            onChange={e => this.galerieSelectChange(e.target.value)}>
                        <option value=""> auswählen ...</option>
                        {!this.state.selectGalerie ? (
                            <option value="">loading</option>) : (this.state.selectGalerie).map((select, index) =>
                            <option
                                key={index} value={select.id}
                                selected={select.id == this.props.selectedGalerie}>{select.name}</option>)}
                    </select>
                </div>
            </div>
        );
    }
}