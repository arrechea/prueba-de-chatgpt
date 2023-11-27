import React from 'react'

export default class FieldBase extends React.Component {

    /**
     *
     * @param field
     * @returns {XML|null}
     */
    PrintHelp(field) {
        let text = field.help_text;
        if (text !== null) {
            return (
                <a className={'special-text-form-info'} data-tooltip-bottom data-tooltipbottom-message={text}>
                    <i className="material-icons small">info</i>
                </a>
            );
        } else {
            return null;
        }
    }

    /**
     *
     * @returns {string}
     */
    GetName() {
        return this.props.name;
    }

    /**
     *
     * @returns {string}
     */
    GetValue() {
        return this.props.value ? this.props.value : this.GetDefaultValue();
    }

    /**
     *
     * @constructor
     */
    GetInputExtraClass() {
        let value = this.props.value ? this.props.value : this.GetDefaultValue();
        return value ?
            'active' : '';
    }

    /**
     * @returns {object}
     * @constructor
     */
    GetField() {
        return this.props.field;
    }

    /**
     * @returns {string}
     */
    GetDefaultValue() {
        let field = this.GetField();
        let defaultValue = field.default_value;

        return defaultValue ? defaultValue : '';
    }

    /**
     *
     * @returns {XML}
     */
    render() {
        return (
            <div>
                not set
            </div>
        )
    }
}
