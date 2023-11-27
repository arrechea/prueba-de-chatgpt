import React from 'react'
import $ from 'jquery'
import LocationRolView from "./LocationRolView";

export default class BrandRolView extends React.Component {
    constructor() {
        super();
        this.state = {
            brandRoles: [],
            cargando: true,
        }
    }

    /**
     *
     * @returns {{brand: {}, brandTree: {}}}
     */
    static get defaultProps() {
        return {
            brand: {},
            brandTree: {},
        }
    }

    /**
     *
     * @returns {*}
     */
    getBrandConfig() {
        let brand = this.props.brand;
        let brandId = brand.id;

        let brandTree = this.props.brandTree;

        if (brandTree.hasOwnProperty(brandId)) {
            return brandTree[brandId];
        }
    }

    /**
     * Call company brands
     * and company posible roles
     **/
    componentWillMount() {
        let componente = this;
        let brand = this.props.brand;

        let url = window.RolesAsignement.urls.getBrandRoles;
        url = url.replace('|', brand.id);

        $.get(url).done((roles) => {
            componente.setState({
                cargando: false,
                brandRoles: roles,
            });
        });
    }

    render() {
        if (this.state.cargando) {
            return null;
        }
        let brand = this.props.brand;

        let brandConfig = this.getBrandConfig();
        let brandRol = brandConfig ? brandConfig.role : '';

        let respuesta = [
            <tr className="odd" key={`treeView-brand-${brand.id}`}>
                <td className="sorting_1" colSpan={2}/>
                <td>{brand.name}</td>
                <td/>
                <td>
                    <select name={`rolbrands[${brand.id}]`} className="select" defaultValue={brandRol}>
                        <option value="">--</option>
                        {this.state.brandRoles.map((rol) => {
                            return (
                                <option value={rol.id} key={`brandRol-${rol.id}`}>{rol.title}</option>
                            )
                        })}
                    </select>
                </td>
            </tr>
        ];
        if (brand.locations.length > 0) {
            brand.locations.forEach(function (location) {
                let locationTree = brandConfig ? brandConfig.locations : '';
                respuesta.push(
                    <LocationRolView location={location} locationTree={locationTree}
                                  key={`treeView-locationContainer-${location.id}`}/>
                )
            });
        }

        return respuesta;
    }
}
