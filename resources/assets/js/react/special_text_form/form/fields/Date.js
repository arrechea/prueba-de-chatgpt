import React from 'react';
import FieldBase from "./FieldBase";

export default class Date extends FieldBase {
    render() {
        let name = this.props.name;
        let value = this.GetValue();
        let field = this.props.field;
        let col = this.props.col;
        let required = this.props.required;
        let counter = this.props.counter;

        return (
            <div className={`${col}`}>
                {/*<input id={`${name}--helper`} hidden={true} required={required}/>*/}
                {required ? (<span className={'red-asterisk'}>*</span>) : null}
                <label htmlFor={`${name}--helper`}>{field.name}  {counter()} {this.PrintHelp(field)}</label>
                <input type={'date'} name={name} id={name} defaultValue={value}/>
            </div>
        );
    }
}
