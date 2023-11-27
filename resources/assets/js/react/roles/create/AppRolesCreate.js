import * as React from "react";

export default class AppRolesCreate extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            role: {},
            company: null,
            brand: null,
            location: null,
            brands: [],
            locations: [],
            infoDefault: {},
            cargando: false,
            company_level: props.company_level
        }
    }

    /**
     * Montado del componente
     */
    componentDidMount() {
        let infoInicialDiv = $('#RolAttributes');
        let info = {};
        let precargarInfoCompany = false;
        if (infoInicialDiv.length) {
            try {
                info = JSON.parse(infoInicialDiv.html());
            } catch (e) {
                console.error(e);
            }
        }
        let firstState = {
            infoDefault: info,
            cargando: true,
        };
        if (!!info.companies_id) {
            precargarInfoCompany = true;
            firstState.company = info.companies_id;
        }
        if (!!info.brands_id) {
            firstState.brand = info.brands_id;
        }
        if (!!info.locations_id) {
            firstState.location = info.locations_id;
        }

        this.setState(firstState, function () {
            this.actualizacionDeElementos(precargarInfoCompany);
        });
    }

    /**
     * Actualizado de componente
     */
    componentDidUpdate() {
        this.actualizacionDeElementos();
    }

    /**
     *
     */
    actualizacionDeElementos(precargarInfoCompany) {
        let component = this;
        $(document).ready(function () {
            let selectorCompany = $(component.refs.company);
            let selectorBrand = $(component.refs.brand);
            let selectorLocation = $(component.refs.location);
            let company_level = component.state.company_level;

            if(!company_level) {
                selectorCompany.select2({
                    width: '100%',
                    minimumInputLength: 1,
                    ajax: {
                        url: window.Roles.urls.company,
                        dataType: 'json',
                        delay: 250,

                        data: function (params) {
                            return {
                                search: params.term,
                            }
                        },
                        processResults: function (data) {
                            let options = [];
                            data.forEach(function (item) {
                                options.push({
                                    id: item['id'],
                                    text: item['name']
                                })
                            });

                            return {results: options}
                        }
                    },
                    placeholder: window.Roles.lang.company_placeholder,
                    allowClear: true,
                }).on('select2:select', function (e) {
                    component.ChangeCompany();
                }).on('select2:unselect', function (e) {
                    component.ChangeCompany();
                });
            }

            selectorBrand.select2({
                width: '100%',
                placeholder: window.Roles.lang.brand_placeholder,
                allowClear: true,
            }).on('select2:select select2:unselect', function (e) {
                component.ChangeBrand(e);
            });

            selectorLocation.select2({
                width: '100%',
                placeholder: window.Roles.lang.location_placeholder,
                allowClear: true,
            }).on('select2:select select2:unselect', function (e) {
                component.ChangeLocation(e);
            });

            if (precargarInfoCompany) {
                /*
                 Preload companies
                 */
                component.ChangeCompany();
            }
        });
    }

    /**
     * Cambio de company
     */
    ChangeCompany() {
        let component = this;
        let company = component.refs.company;

        if (company.value !== '') {
            //Seleccion de una marca
            $.get(window.Roles.urls.brandsLocations, {
                'companyId': company.value
            }).done(function (data) {
                //Seleccion de una marca
                component.setState({
                    company: company.value,
                    brands: !!data.brands ? data.brands : [],
                    locations: !!data.locations ? data.locations : [],
                });
            });
        } else {
            //Sin marca seleccionada
            component.setState({
                company: null,
                brand: null,
                location: null,
                brands: [],
                locations: [],
            });
        }
    }

    /**
     *
     * @param event
     */
    ChangeBrand(event) {
        let brand = event.target.value;
        if (brand !== '') {
            //checar marcas de la compania y actualizar state
            this.setState({
                brand: brand,
                location: null
            });
        } else {
            this.setState({
                brand: null,
                location: null,
            });
        }
    }

    /**
     *
     * @param event
     */
    ChangeLocation(event) {
        let location = event.target.value;
        if (location !== '') {
            this.setState({
                location: location,
            });
        } else {
            this.setState({
                location: null,
            })
        }
    }

    /**
     * Company selector
     * @returns {XML}
     */
    PrintCompanySelector() {
        let companyDefault = this.state.infoDefault.company;
        let companyDefaultObject = null;
        if (!!companyDefault) {
            companyDefaultObject = <option value={companyDefault.id}>{companyDefault.name}</option>;
        }
        let company_level = this.state.company_level;

        if (company_level) {
            return (
                <input ref={'company'} hidden={true} defaultValue={this.state.company} name={'companies_id'}/>
            );
        } else {
            return (
                <div className="input-field col s12 m3">
                    <select name="companies_id" id="RoleSelectCompany" className="select"
                            disabled={this.state.company_level}
                            ref="company" defaultValue={this.state.company ? this.state.company : ''}>
                        <option value="">--</option>
                        {companyDefaultObject}
                    </select>
                </div>
            );
        }
    }

    /**
     *
     * @returns {*}
     * @constructor
     */
    PrintBrandSelector() {
        if (this.state.company === null) {
            return null;
        }

        return (
            <div className="input-field col s12 m3">
                <select name="brands_id" id="RoleSelectBrand" className="select" ref={'brand'}
                        value={this.state.brand ? this.state.brand : ''} onChange={this.ChangeBrand.bind(this)}>
                    <option value="">--</option>
                    {this.state.brands.map(function (brand) {
                        return (
                            <option value={brand.id} key={`brand--${brand.id}`}>{brand.name}</option>
                        )
                    })}
                </select>
            </div>
        );
    }

    /**
     *
     * @returns {*}
     * @constructor
     */
    PrintLocationSelector() {
        if (this.state.company === null || this.state.brand === null)
            return null;

        let brand = parseInt(this.state.brand);

        return (
            <div className="input-field col s12 m3">
                <select name="locations_id" id="RoleSelectLocation" className="select"
                        ref={'location'} value={this.state.location ? this.state.location : ''}
                        onChange={this.ChangeLocation.bind(this)}>
                    <option value="">--</option>
                    {this.state.locations.map(function (location) {
                        if (parseInt(location.brands_id) !== brand)
                            return null;

                        return (
                            <option value={location.id} key={`location--${location.id}`}>{location.name}</option>
                        )
                    })}
                </select>
            </div>
        );
    }

    /**
     *
     * @returns {XML}
     */
    render() {
        if (this.state.cargando === false) {
            return null
        }
        return (
            <div>
                <div className="row">
                    <div className="input-field col s12 m3">
                        <input id="role-name" className="validate" type="text" name="title" required
                               defaultValue={this.state.infoDefault.title}/>
                        <label htmlFor="role-name">{window.Roles.lang.role_name}</label>
                    </div>

                    {this.PrintCompanySelector()}

                    {this.PrintBrandSelector()}

                    {this.PrintLocationSelector()}
                </div>
            </div>
        )
    }
}
