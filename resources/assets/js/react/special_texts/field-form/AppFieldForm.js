import * as React from 'react'
import StorageField from "./StorageField";

export default class AppFieldForm extends React.Component {
    constructor(props) {
        super();
        this.state = {
            cargando: false,
            required: false,
            validation: this.FieldValue('validation'),
            type: this.FieldValue('type'),
            validation_label_class: this.active('validation'),
            options: StorageField.options,
        };

        this.handleValidationChange = this.handleValidationChange.bind(this);
        this.handleRequiredChange = this.handleRequiredChange.bind(this);
        this.addRequiredToValidation = this.addRequiredToValidation.bind(this);
        this.checkRequired = this.checkRequired.bind(this);
        this.changeRequiredState = this.changeRequiredState.bind(this);
        this.addOption = this.addOption.bind(this);
        this.handelTypeChange = this.handelTypeChange.bind(this);
    }

    componentDidMount() {
        $('select').material_select();
        this.changeRequiredState(this.state.validation);
        let component = this;
        $('#type').on('change', function (e) {
            component.handelTypeChange(e);
        })
    }

    PrintSlugInput() {
        if (StorageField.field !== null) {
            return (
                <div className="col s12 input-field">
                    <input type="text" className="input"
                           defaultValue={this.FieldValue('slug')} disabled={true}/>
                    <label className="active">
                        {StorageField.lang.Slug}</label>
                </div>
            )
        }
    }

    isset(val, withValue = false, def = '', emptyString = false) {
        let check = typeof val !== 'undefined' && val !== null;
        let value = emptyString ? check && val !== '' : check;
        return withValue ? (value ? val : def) : value;
    }

    active(val) {
        return this.isset(this.FieldValue(val), true) ? 'active' : '';
    }

    issetField(val) {
        return typeof StorageField.field === 'object' && this.isset(StorageField.field) ? (val in StorageField.field) : false;
    }

    FieldValue(val, def = '') {
        return this.issetField(val) ? (this.isset(StorageField.field[val], true, def, true)) : def;
    }

    PrintTypes() {
        let results = [];
        let types = StorageField.types;
        for (var key in types) {
            if (types.hasOwnProperty(key)) {
                results.push({
                    val: key,
                    text: types[key]
                });
            }
        }

        return results.map(function (option) {
            return (
                <option value={option.val} onChange={function () {
                } } key={`field-types-${option.val}`}>{option.text}</option>
            );
        });
    }

    handleValidationChange(e) {
        this.setState({
            validation: e.target.value,
        });

        this.changeRequiredState(e.target.value);
    }

    handleRequiredChange(e) {
        let required = this.refs.required.checked;
        this.setState({
            required: required,
        });

        this.addRequiredToValidation(required);
    }

    addRequiredToValidation(required = false) {
        let validation = this.state.validation;
        let has_required = this.checkRequired(validation);
        if (required) {
            if (!has_required) {
                validation += '|required'
            }
        } else {
            if (has_required) {
                validation = validation.replace('required', '');
            }
        }

        validation = validation.replace(/\|$/, '');
        validation = validation.replace(/^\|+/, '');

        let label_class = validation !== '' ? 'active' : '';

        this.setState({
            validation: validation,
            validation_label_class: label_class,
        });
    }

    changeRequiredState(validation) {
        let required = this.checkRequired(validation);
        this.setState({
            required: required
        })
    }

    checkRequired(validation) {
        return validation.search(/required/i) >= 0;
    }

    PrintOptions() {
        let component = this;
        return component.state.options.map(function (option, index) {
            return (
                <li className={'row'} key={`field-options-${index}`}>
                    <input hidden={true} name={`options[${index}][id]`} defaultValue={option.id || 0}/>
                    <div className="col m10 input-field">
                        <input type="text" name={`options[${index}][value]`} defaultValue={option.value}
                               className="input__white"/>
                    </div>

                    <div className={'secondary-content col m2'}>
                        {
                            option.id ?
                                <div>ID_{option.id}</div>
                                :
                                null
                        }
                        <a onClick={() => component.deleteOption(index)} className={'btn btn-small'}><i
                            className={'material-icons'}>delete</i></a>
                    </div>
                </li>
            );
        })
    }

    addOption(e) {
        let new_option = this.refs.new_option.value.trim();
        let options = this.state.options;
        if (new_option !== '') {
            options.push({
                value: new_option
            });
            this.setState({
                options: options,
            });
            this.refs.new_option.value = '';
        }
    }

    deleteOption(index) {
        let options = this.state.options;
        delete options[index];
        this.setState({
            options: options
        })
    }

    handelTypeChange(e) {
        let type = this.refs.type.value;
        this.setState({
            type: type,
        });
    }

    PrintOptionsSection() {
        let type = this.state.type;
        let component = this;
        if (type === 'select' || type === 'radio' || type === 'checkbox') {
            return (
                <div className={'col s12 catalog-field-options'}>
                    <div className="input-field">
                        <label className="active active__big">{StorageField.lang.Options}</label>
                    </div>
                    <ul id={'options-list'}>
                        {component.PrintOptions()}
                    </ul>
                    <div className="row">
                        <div className="col m10">
                            <input type={'text'} ref={'new_option'} className="input__white"/>
                        </div>
                        <div className={'col m2'}>
                            <a className={'btn btn-small'} onClick={component.addOption}>+</a>
                        </div>
                    </div>
                </div>
            );
        }

        return null;
    }

    render() {
        return (
            <div>
                <div className="panelcombos col panelcombos_full">
                    <div className="col s12 m8">
                        <div className="row">
                            {this.PrintSlugInput()}

                            <div className="col s12 input-field">
                                <input type="text" className="input" id="name" name="name"
                                       defaultValue={this.FieldValue('name')}/>
                                <label htmlFor="name"
                                       className={this.active('name')}>
                                    {StorageField.lang.Name}</label>
                            </div>

                            <div className="col s12 input-field">
                                <input type="text" className="input" id="help_text" name="help_text"
                                       defaultValue={this.FieldValue('help_text')}/>
                                <label htmlFor="name"
                                       className={this.active('help_text')}>
                                    {StorageField.lang.HelpText}</label>
                            </div>

                            <div className="col s12 input-field">
                                <input type="text" className="input" id="validation" name="validation"
                                       value={this.state.validation} onChange={this.handleValidationChange}/>
                                <label htmlFor="validation"
                                       className={this.state.validation_label_class}>
                                    {StorageField.lang.Validation}</label>
                            </div>

                            <div className="col s12 input-field">
                                <input type="text" className="input" id="default_value" name="default_value"
                                       defaultValue={this.FieldValue('default_value')}/>
                                <label htmlFor="default_value"
                                       className={this.active('default_value')}>
                                    {StorageField.lang.DefaultValue}</label>
                            </div>
                        </div>
                    </div>

                    <div className="col s12 m4">
                        <div className="row">
                            <div className="input-field">
                                <select name="type" id="type" value={this.state.type}
                                        ref={'type'} onChange={function () {
                                }}>
                                    <option value={''}>--</option>
                                    {this.PrintTypes()}
                                </select>
                                <label htmlFor="type">{StorageField.lang.Type}</label>
                            </div>

                            <div className="input-field">
                                <input type="number" className="input" id="order" name="order"
                                       defaultValue={this.FieldValue('order', 0)} min="0"/>
                                <label htmlFor="order" className="active">
                                    {StorageField.lang.Order}</label>
                            </div>

                            <div className="input-field catalog-field-checkbox">
                                <input type="checkbox" className="checkbox" id="required"
                                       name="required" ref={'required'} checked={this.state.required}
                                       onChange={this.handleRequiredChange}/>
                                <label htmlFor="required">{StorageField.lang.Required}</label>
                            </div>

                            <div className="input-field catalog-field-checkbox">
                                <input type="checkbox" className="checkbox" id="duplicable"
                                       name="duplicable" defaultChecked={this.FieldValue('can_repeat')}/>
                                <label htmlFor="duplicable">{StorageField.lang.Duplicable}</label>
                            </div>

                            <div className="input-field catalog-field-checkbox">
                                <input type="checkbox" className="checkbox" id="active"
                                       name="active" defaultChecked={this.FieldValue('status') === 'active'}/>
                                <label htmlFor="active">{StorageField.lang.Active}</label>
                            </div>

                            <div className="input-field catalog-field-checkbox">
                                <input type="checkbox" className="checkbox" id="show_in_list"
                                       name="show_in_list" defaultChecked={!this.FieldValue('hidden_in_list', true)}/>
                                <label htmlFor="show_in_list">{StorageField.lang.ShowInList}</label>
                            </div>

                            <div className="input-field catalog-field-checkbox">
                                <input type="checkbox" className="checkbox" id="sortable"
                                       name="sortable" defaultChecked={this.FieldValue('sortable')}/>
                                <label htmlFor="sortable">{StorageField.lang.Sortable}</label>
                            </div>
                        </div>
                    </div>
                    <div className="clear AppFieldForm--OptionSection">
                        {this.PrintOptionsSection()}
                    </div>
                </div>
                <div className="edit-buttons" style={{'marginTop': '15px'}}>
                    <button type="submit" className="waves-effect waves-light btn btnguardar modal-close right"><i
                        className="material-icons right small">save</i>{StorageField.lang.Save}</button>
                </div>
            </div>
        );
    }
}
