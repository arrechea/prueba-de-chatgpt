import React from 'react'
import $ from 'jquery'

export default class LocationRolView extends React.Component {
    constructor() {
        super();
        this.state = {
            locationRoles: [],
            cargando: true,
        }
    }

    /**
     *
     * @returns {{brand: {}, brandTree: {}}}
     */
    static get defaultProps() {
        return {
            location: {},
            locationTree: {},
        }
    }

    /**
     *
     * @returns {*}
     */
    getLocationConfig() {
        let location = this.props.location;
        let locationId = location.id;

        let locationTree = this.props.locationTree;

        if (locationTree.hasOwnProperty(locationId)) {
            return locationTree[locationId];
        }
    }

    componentWillMount() {
        let componente = this;
        let location = this.props.location;

        let url = window.RolesAsignement.urls.getLocationRoles;
        url = url.replace('|', location.id);

        $.get(url).done((roles) => {
            componente.setState({
                cargando: false,
                locationRoles: roles,
            });
        });
    }

    render() {
        if (this.state.cargando) {
            return null;
        }
        let location = this.props.location;
        let locationConfig = this.getLocationConfig();
        let locationRol = locationConfig ? locationConfig.role : '';

        return (
            <tr className="odd">
                <td className="sorting_1" colSpan={3}/>
                <td>{location.name}</td>
                <td>
                    <select name={`rollocations[${location.id}]`} className="select" defaultValue={locationRol}>
                        <option value="">--</option>
                        {this.state.locationRoles.map((rol) => {
                            return (
                                <option value={rol.id} key={`locationRol-${rol.id}`}>{rol.title}</option>
                            )
                        })}
                    </select>
                </td>
            </tr>
        )
    }
}
