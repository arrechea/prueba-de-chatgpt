import React from 'react';
import FieldBase from "./FieldBase";

export default class Textarea extends FieldBase {
    render() {
        let counter = this.props.counter;
        let value = this.GetValue();
        let active = this.GetInputExtraClass();

        return (
            <div className={`${this.props.col}`}>
                <div className={'input-field col s11'}>
                    <textarea className={'materialize-textarea'} id={this.props.name}
                              name={this.props.name} defaultValue={value}
                              required={this.props.required}>
                                    </textarea>
                    <label htmlFor={this.props.name} className={active}>{this.props.field.name}  {counter()} {this.PrintHelp(this.props.field)}</label>
                </div>
            </div>
        );
    }
}
