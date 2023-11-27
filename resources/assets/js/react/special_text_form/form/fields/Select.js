import React from 'react';
import FieldBase from "./FieldBase";

export default class Select extends FieldBase {
    render() {
        let name = this.props.name;
        let value = this.GetValue();
        let field = this.props.field;
        let field_key = this.props.field_key;
        let active = this.GetInputExtraClass();
        let col = this.props.col;
        let required = this.props.required;
        let counter = this.props.counter;

        return (
            <div className={`input-field ${col}`}>
                <select name={name} id={name} defaultValue={value} required={required}>
                    <option value={''}>--</option>
                    {field.catalog_field_options.map(function (option) {
                        return (
                            <option
                                key={`${field_key}--${option.id}`}
                                value={option.value}>{option.value}</option>)
                    })}
                </select>
                <label htmlFor={name} className={active}>{field.name} {counter()} {this.PrintHelp(field)}</label>
            </div>
        );
    }
}
