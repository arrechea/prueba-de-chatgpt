import React from 'react';
import Field from '../fields/Field';

export default class Group extends React.Component {
    constructor(props) {
        super();
    }

    PrintGroup(group) {
        let component = this;
        let indices = component.props.getGroupIndices(group);

        return (
            <div className={`group-wrapper--${group.id}`} key={`group-wrapper--${group.id}`}>
                {indices.map(function (value_group_index, group_index) {
                    let counter = function () {
                        if (group.can_repeat) {
                            return (
                                <small className={'group-counter'}>{group_index + 1}/{indices.length}</small>
                            );
                        } else {
                            return null;
                        }
                    };
                    return (
                        <div key={`group-wrapper--group--${group.id}--${value_group_index}`}>
                            <h5 className={''}>{group.name} {counter()}</h5>
                            <div className={'card-panel panelcombos'}
                                 key={`panel-group-${group.id}--${value_group_index}`}
                                 ref={`panel-group-${group.id}--${value_group_index}`}
                                 id={`panel-group-${group.id}--${value_group_index}`}>

                                <div className={'col s12'}>
                                    <div className={'row'}>
                                        {component.PrintFields(group.active_fields, value_group_index, group_index)}
                                    </div>
                                </div>
                                <div className={'row'}>
                                    <hr/>
                                    {component.PrintRemoveButton(group, value_group_index)} {component.PrintAddButton(group)}
                                </div>
                            </div>
                        </div>
                    );
                })}
            </div>
        );
    }

    PrintFields(fields, value_group_index, group_index) {
        let component = this;
        return fields.map(function (field) {
            return (
                <div key={`field-wrapper--${field.id}`}>
                    <Field
                        field={field}
                        value_group_index={value_group_index}
                        group_index={group_index}
                        values={component.props.values}
                        addField={component.props.addField}
                        getFieldIndices={component.props.getFieldIndices}
                        getFieldValue={component.props.getFieldValue}
                        removeField={component.props.removeField}
                    />
                </div>

            );
        });
    }


    PrintAddButton(group, key) {
        let component = this;
        if (group.can_repeat) {
            return (
                <div className={'catalog-form-control-buttons'}>
                    <a className={'btn btn-floating right'} onClick={() => component.props.addGroup(group, key)}><i
                        className={'material-icons'}>add</i></a>
                </div>
            );
        }
    }

    PrintRemoveButton(group, group_index) {
        let component = this;
        let indices = component.props.getGroupIndices(group);
        if (indices.length > 1) {
            return (
                <div className={'catalog-form-control-buttons'}>
                    <a className={'btn btn-floating right'}
                       onClick={() => component.props.removeGroup(group, group_index)}><i
                        className={'material-icons'}>remove</i></a>
                </div>
            );
        }
    }

    render() {
        let group = this.props.group;
        return (
            <div key={`group-container--${group.id}`}>
                {this.PrintGroup(group)}
            </div>
        )
    }
}
