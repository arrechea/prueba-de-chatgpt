import React from 'react';
import FieldBase from "./FieldBase";

export default class Default extends FieldBase {

    render() {
        let name = this.props.name;
        let value = this.GetValue();
        let field = this.props.field;
        let col = this.props.col;
        let active = this.GetInputExtraClass();
        let required = this.props.required;
        let counter = this.props.counter;

        return (
            <div className={`input-field ${col}`}>
                <input name={name} id={name} type={field.type} defaultValue={value}
                       required={required}/>
                <label htmlFor={name} className={active}>{field.name} {counter()} {this.PrintHelp(field)}</label>
            </div>
        );
    }
}
