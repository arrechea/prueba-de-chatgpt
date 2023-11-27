import * as React from "react"
import $ from 'jquery'
import TreeViewList from "./components/TreeViewList";
import StoreCompanyTree from "./StoreCompanyTree";

export default class AppAssignRol extends React.Component {
    constructor() {
        super();
        this.state = {
            lang: window.RolesAsignement.lang,
            companies: window.RolesAsignement.companies,
            rolesGafaFit: window.RolesAsignement.rolesGafaFit,
            //Admin
            adminToEdit: window.RolesAsignement.adminToEdit,
            adminRoles: [],
            //Elementos existentes
            companyTree: {},
            cargando: true,
            companyBlocked: window.RolesAsignement.companyBlocked
        };
        //Subscribe
        StoreCompanyTree.addListener(this.ListenerCompanyTree.bind(this))
    }

    /**
     * Listener companyTree
     */
    ListenerCompanyTree() {
        this.setState({
            companyTree: StoreCompanyTree.tree,
        })
    }

    /**
     *
     * @returns {{brands: {}, role: null}}
     */
    static getCompanyTreeModel(model) {
        return {
            brands: {},
            model: model,
            role: null
        }
    }

    /**
     *
     * @returns {{locations: {}, role: null}}
     */
    static getBrandTreeModel(model) {
        return {
            locations: {},
            model: model,
            role: null
        }
    }

    /**
     *
     * @returns {{role: null}}
     */
    static getLocationTreeModel(model) {
        return {
            model: model,
            role: null
        }
    }

    /**
     * Presetearemos bastantes elementos
     * Haremos un loop entre los permisos del usuario y añadiremos al companyTree companies, brands y locations
     */
    componentDidMount() {
        let componente = this;
        let url = window.RolesAsignement.urls.getAdminRoles;

        $.get(url).done((roles) => {
            let companyTree = {};
            roles.forEach(function (rol) {
                let rolesPadres = rol.parentsLevels;
                let companyId = null;
                let brandId = null;
                let locationId = null;

                if (rolesPadres.length === 3) {
                    //es location
                    companyId = rolesPadres[0].id;
                    brandId = rolesPadres[1].id;
                    locationId = rol.pivot.assigned_id;
                } else if (rolesPadres.length === 2) {
                    //brand
                    companyId = rolesPadres[0].id;
                    brandId = rol.pivot.assigned_id;
                } else {
                    //es company o gafafit
                    if (rol.pivot.assigned_type) {
                        //company
                        companyId = rol.pivot.assigned_id;
                    } else {
                        //gafafit
                    }
                }
                if (companyId) {
                    if (!companyTree.hasOwnProperty(companyId)) {
                        companyTree[companyId] = AppAssignRol.getCompanyTreeModel(rolesPadres[0]);
                    }
                    //Si no tiene brand seteamos rol sino continuamos
                    if (!brandId) {
                        companyTree[companyId].role = rol.id;
                    } else {
                        if (!companyTree[companyId].brands.hasOwnProperty(brandId)) {
                            companyTree[companyId].brands[brandId] = AppAssignRol.getBrandTreeModel(rolesPadres[1]);
                        }
                        //Si no tiene location seteamos rol sino continuamos
                        if (!locationId) {
                            companyTree[companyId].brands[brandId].role = rol.id;
                        } else {
                            //Si hay location pues la configuramos
                            if (!companyTree[companyId].brands[brandId].locations.hasOwnProperty(locationId)) {
                                companyTree[companyId].brands[brandId].locations[locationId] = AppAssignRol.getLocationTreeModel(rolesPadres[2]);
                            }
                            companyTree[companyId].brands[brandId].locations[locationId].role = rol.id;
                        }
                    }
                }
            });
            // console.log(roles);
            StoreCompanyTree.set(companyTree, () => {
                componente.setState({
                    adminRoles: roles,
                    cargando: false
                }, () => {
                    if (componente.state.companyBlocked) {
                        //Activate company blocked
                        let companyId = componente.state.companyBlocked;
                        let companiesActivas = StoreCompanyTree.tree;
                        if (!companiesActivas.hasOwnProperty(companyId)) {
                            let company = this.BuscarEnCompanyPorId(companyId);
                            companiesActivas[companyId] = AppAssignRol.getCompanyTreeModel(company);
                        }
                        StoreCompanyTree.set(companiesActivas);
                    }
                });
            });
        });
    }

    /**
     * Get rol of player
     */
    getAdminRolGafaFit() {
        let rolesAdmin = this.state.adminRoles;
        let rolGafaFit = rolesAdmin.filter((rol) => {
            return rol.pivot.assigned_type === null;
        });
        return rolGafaFit.shift()
    }

    /**
     * Add company
     * @constructor
     */
    AddCompany() {
        let selector = this.refs.companySelect;
        let valorSelector = selector.value;
        if (valorSelector !== '') {
            let companiesActivas = StoreCompanyTree.tree;
            if (!companiesActivas.hasOwnProperty(valorSelector)) {
                let company = this.BuscarEnCompanyPorId(valorSelector);
                companiesActivas[valorSelector] = AppAssignRol.getCompanyTreeModel(company);
            }
            StoreCompanyTree.set(companiesActivas);
            selector.value = '';
        }
    }

    /**
     *
     * @param id
     * @constructor
     */
    BuscarEnCompanyPorId(id) {
        id = parseInt(id);
        let companies = this.state.companies;
        companies = companies.filter((company) => {
            return parseInt(company.id) === id;
        });
        return companies[0];
    }

    /**
     * Role selector in gafafit level
     * @returns {*}
     */
    PrintRolGafaFit() {
        if (this.state.rolesGafaFit.length < 1) {
            return null;
        }
        let lang = this.state.lang;

        let roleAdminGafaFit = this.getAdminRolGafaFit();

        return (
            <tr>
                <td>GafaFit</td>
                <td>
                    <select className="select" onChange={this.AddCompany.bind(this)} ref="companySelect">
                        <option value="">{lang.addCompany}</option>
                        {this.state.companies.map((company) => {
                            return (
                                <option value={company.id}
                                        key={`addCompany-${company.id}`}>{company.name}</option>
                            )
                        })}
                    </select>
                </td>
                <td colSpan="2"/>
                <td>
                    <select name="rolgafafit" className="select"
                            defaultValue={!!roleAdminGafaFit ? roleAdminGafaFit.id : ''}>
                        <option value="">--</option>
                        {this.state.rolesGafaFit.map((rol) => {
                            return (
                                <option value={rol.id}
                                        key={`rolGafafit-${rol.id}`}>{rol.title}</option>
                            )
                        })}
                    </select>
                </td>
            </tr>
        )
    }

    /**
     *
     * @returns {XML}
     */
    render() {
        if (this.state.cargando) {
            return null;
        }
        let lang = this.state.lang;

        return (
            <div>
                <table className="striped rolesassignment">
                    <thead>
                    <tr>
                        <th data-field="GafaFit">GafaFit</th>
                        <th data-field="Companies">{lang.companies}</th>
                        <th data-field="Brands">{lang.brands}</th>
                        <th data-field="Locations">{lang.locations}</th>
                        <th data-field="Roles">{lang.roles}</th>
                    </tr>
                    </thead>
                    <tbody>
                    {this.PrintRolGafaFit()}
                    <TreeViewList companyBlocked={this.state.companyBlocked}
                                  companyTree={this.state.companyTree}/>
                    </tbody>
                </table>

            </div>
        )
    }
}
