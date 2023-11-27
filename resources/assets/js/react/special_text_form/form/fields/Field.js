import React from 'react';
import Textarea from './Textarea';
import Select from './Select';
import Radio from './Radio';
import File from './File';
import Date from './Date';
import Default from './Default';
import Checkbox from './Checkbox';

export default class Field extends React.Component {
    constructor(props) {
        super();

        this.PrintField = this.PrintField.bind(this);
        this.getFieldName = this.getFieldName.bind(this);
    }

    PrintField(field, value_group_index, group_index) {
        let component = this;
        let indices = component.props.getFieldIndices(field, value_group_index);
        return indices.map(function (value_field_index, field_index) {
                let name = component.getFieldName(field, group_index, field_index);
                let value = component.props.getFieldValue(field, value_group_index, value_field_index);
                let active = value !== '' ? 'active' : '';
                let field_key = `field--${field.catalogs_groups_id}--${value_group_index}--${field.id}--${value_field_index}`;
                let field_element = function () {
                    return null
                };
                let col = field.can_repeat ? 'col s9 m10 l11' : 'col s12';
                let validation = field.validation === null ? '' : field.validation;
                let required = validation.indexOf('required') !== -1;

                let counter = function () {
                    if (field.can_repeat) {
                        return (
                            <span className={''}>( {field_index + 1}/{indices.length} )</span>
                        );
                    } else {
                        return null;
                    }
                };
                switch (field.type) {
                    case 'textarea':
                        field_element = function () {
                            return (
                                <Textarea col={col} active={active} name={name} value={value} field={field}
                                          required={required} counter={counter}/>
                            );
                        };
                        break;
                    case 'select':
                        field_element = function () {
                            return (
                                <Select col={col} active={active} name={name} value={value} field={field}
                                        field_index={field_index} field_key={field_key} required={required} counter={counter}/>
                            );
                        };
                        break;
                    case 'radio':
                        field_element = function () {
                            return (
                                <Radio col={col} name={name} value={value} field={field} field_index={field_index}
                                       field_key={field_key} required={required} counter={counter}/>
                            );
                        };
                        break;
                    case 'file':
                        field_element = function () {
                            return (
                                <File col={col} name={name} value={value} field={field} field_index={field_index}
                                      field_key={field_key} required={required} counter={counter}/>
                            );
                        };
                        break;
                    case 'date':
                        field_element = function () {
                            return (
                                <Date col={col} name={name} value={value} field={field} field_index={field_index}
                                      field_key={field_key} required={required} counter={counter}/>
                            );
                        };
                        break;
                    case 'checkbox':
                        field_element = function () {
                            return (
                                <Checkbox col={col} active={active} name={name} value={value} field={field}
                                          required={required} counter={counter}/>
                            );
                        };
                        break;
                    default:
                        field_element = function () {
                            return (
                                <Default col={col} active={active} name={name} value={value} field={field}
                                         required={required} counter={counter}/>
                            );
                        }
                }

                return (
                    <div key={field_key} className={'col s12'}>
                        {field_element()}
                        {component.PrintButtons(field, value_field_index, value_group_index)}
                    </div>
                );
            }
        );
    }

    PrintButtons(field, value_field_index, value_group_index) {
        let indices = this.props.getFieldIndices(field, value_group_index);
        if (field.can_repeat) {
            let component = this;
            let add_btn = function () {
                return (
                    <a className={'btn btn-small col s12 special-text-field-button'}
                       onClick={() => component.props.addField(field, value_group_index)}><i
                        className={'material-icons'}>add</i>
                    </a>
                );
            };

            if (indices.length > 1) {
                return (
                    <div className={'special-text-field-buttons-wrapper'}>
                        {add_btn()}
                        <a className={'btn btn-small col s12 special-text-field-button'}
                           onClick={() => component.props.removeField(field, value_group_index, value_field_index)}><i
                            className={'material-icons'}>remove</i>
                        </a>
                    </div>
                );
            } else {
                return (
                    <div className={'special-text-field-buttons-wrapper'}>
                        {add_btn()}
                    </div>
                );
            }
        }
    }

    getFieldName(field, group_index, field_index) {
        let base_name = 'custom_fields';
        return `${base_name}[${field.catalogs_groups_id}][${group_index}][${field.id}][${field_index}]`
    }

    render() {
        return (
            <div className={''}>
                {this.PrintField(this.props.field, this.props.value_group_index, this.props.group_index)}
            </div>
        );
    }
}
