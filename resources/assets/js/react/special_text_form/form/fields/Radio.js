import React from 'react';
import FieldBase from "./FieldBase";

export default class Radio extends FieldBase {
    render() {
        let name = this.props.name;
        let value = this.GetValue();
        let field = this.props.field;
        let field_key = this.props.field_key;
        let col = this.props.col;
        let required = this.props.required;
        let counter = this.props.counter;

        return (
            <div className={`${col} radio-container`}>
                <input hidden={true} id={name} required={required}/>
                <label htmlFor={name} className="label_fixed">{field.name} {counter()} {this.PrintHelp(field)}</label>
                {field.catalog_field_options.map(function (option) {
                    let id = `${option.id}`;
                    return (
                        <div key={`${field_key}--${id}`}>
                            <input id={`${name}[${id}]`} value={option.value} name={name}
                                   type={field.type} defaultChecked={value === option.value}/>
                            <label htmlFor={`${name}[${id}]`}>{option.value}</label>
                        </div>
                    );
                })}
            </div>
        );
    }
}
