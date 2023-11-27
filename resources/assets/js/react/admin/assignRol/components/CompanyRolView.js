import React from 'react'
import $ from 'jquery'
import BrandRolView from "./BrandRolView";

export default class CompanyRolView extends React.Component {
    /**
     *
     */
    constructor() {
        super();
        this.state = {
            companyBrands: [],
            companyRoles: [],
            cargando: true,
        }
    }

    static get defaultProps() {
        return {
            company: {},
        }
    }

    /**
     * Call company brands
     * and company posible roles
     **/
    componentWillMount() {
        let componente = this;
        let company = this.props.company;

        let url = window.RolesAsignement.urls.getCompanyRolesAndBrands;
        url = url.replace('|', company.model.id);

        $.get(url).done((data) => {
            componente.setState({
                cargando: false,
                companyRoles: data.roles,
                companyBrands: data.brands
            });
        });
    }

    /**
     *
     **/
    render() {
        if (this.state.cargando) {
            return null;
        }
        let company = this.props.company;
        let brandsModels = this.state.companyBrands;

        let respuesta = [
            <tr className="even" key={`treeView-company-${company.model.id}`}>
                <td className="sorting_1"/>
                <td>{company.model.name}</td>
                <td/>
                <td/>
                <td>
                    <select name={`rolcompanies[${company.model.id}]`} className="select" defaultValue={company.role}>
                        <option value="">--</option>
                        {this.state.companyRoles.map((rol) => {
                            return (
                                <option value={rol.id} key={`companyRol-${rol.id}`}>{rol.title}</option>
                            )
                        })}
                    </select>
                </td>
            </tr>
        ];

        brandsModels.forEach(function(brand){
            respuesta.push(
                <BrandRolView brand={brand} brandTree={company.brands} key={`treeView-brandContainer-${brand.id}`}/>
            )
        });

        return respuesta;
    }
}
