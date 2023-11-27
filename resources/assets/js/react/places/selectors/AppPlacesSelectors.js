import * as React from 'react';

export default class AppPlacesSelectors extends React.Component {
    constructor(props) {
        super();
        this.state = {
            country: null,
            state: window.Places.default.state,
            city: window.Places.default.city,
            countries: window.Places.countries,
            states: window.Places.states,
            cities: window.Places.cities,
            lang: window.Places.lang,
            default: window.Places.default,
        }
    }

    componentDidMount() {
        let component = this;

        $(document).ready(function () {
            let country = $(component.refs.country);
            let state = $(component.refs.state);
            country.material_select();
            state.material_select();
            country.change(function (e) {
                component.ChangeCountry();
                state.trigger('contentChanged');
            });
            state.change(function (e) {
                component.ChangeState();
            });
        });
    }

    componentDidUpdate() {
        inputsAsterisk();
    }

    ChangeCountry() {
        let component = this;
        let country = component.refs.country;

        if (country.value !== '' && country.value !== '-') {
            //Seleccion de un país
            let countries = component.state.countries;
            let selected = countries.find(x => x.id === parseInt(country.value));
            $.get('/api/places/countries/' + selected.code + '/states').done(function (data) {
                component.setState({
                    country: country.value,
                    states: !!data ? data : [],
                    cities: [],
                });
                $(component.refs.state).material_select();
            });
        } else {
            component.setState({
                country: null,
                state: null,
                states: [],
                cities: [],
            });
            $(component.refs.state).material_select();
        }
    }

    ChangeState() {
        let component = this;
        let state = component.refs.state;

        if (state.value !== '') {
            component.setState({
                state: state.value,
                cities: [],
            });
        } else {
            component.setState({
                state: null,
                cities: [],
            });
        }
    }

    setValue() {
        return false;
    }

    render() {
        let component = this;
        let options = component.state.countries.map(function (country) {
            return (
                <option key={`country--${country.code}`}
                        value={country.id}>{country.name}</option>
            );
        });
        let {
            country_required,
            state_required,
            city_required
        } = component.props;

        return (
            <div className={'col s12 m12 l10'} style={{paddingBottom: '30px',paddingLeft: '0'}}>
                <div className={'row'}>
                    <div className={'col s12 m12 l4 place-selector'}>
                        <div className={'input-field'}>
                            <select defaultValue={this.state.default.country}
                                    className={'col s12'} ref={'country'}
                                    id={'country-selector'}
                                    name={'countries_id'}
                                    required={country_required}>
                                <option value={''}></option>
                                {options}
                            </select>
                            <label htmlFor={'country-selector'}>
                                {this.state.lang.country}
                            </label>
                        </div>
                    </div>
                    <div className={'col s12 m12 l4 place-selector'}>
                        <div className={'input-field'}>
                            <select className={'col s12'} ref={'state'}
                                    id={'state-selector'}
                                    name={'country_states_id'} defaultValue={this.state.default.state}
                                    required={state_required}>
                                <option value={''}></option>
                                {this.state.states.map(function (state) {
                                    return (
                                        <option value={state.id}
                                                key={`state--${state.id}`}>{state.name}</option>
                                    )
                                })}
                            </select>
                            <label htmlFor={'state-selector'}>
                                {this.state.lang.state}
                            </label>
                        </div>
                    </div>
                    <div className={'col s12 m12 l4'}>
                        <div className={'input-field'}>
                            <input type={'text'} style={{width: '100%'}}
                                   className={'input'} ref={'city'}
                                   id={'city-selector'}
                                   name={'city'} defaultValue={this.state.default.city} required={city_required}/>
                            <label htmlFor={'city-selector'}
                                   className={this.state.default.city !== null ? 'active' : ''}>
                                {this.state.lang.city}
                            </label>
                        </div>
                    </div>
                    <div style={{display: 'none'}}>
                        <div>
                            {this.state.states.map(function (state) {
                                return (
                                    <div key={`states-selection--${state.id}`}>
                                        <input key={`states-selection-name--${state.id}`} style={{display: 'none'}}
                                               name={`states[${state.id}][name]`} value={state.name}
                                               onChange={component.setValue}/>
                                        <input key={`states-selection-id--${state.id}`} style={{display: 'none'}}
                                               name={`states[${state.id}][id]`} value={state.id}
                                               onChange={component.setValue}/>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}
