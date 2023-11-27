import * as React from 'react'
import FormSpecialText from "./FormSpecialText";
import StorageSpecialTexts from "./StorageSpecialText";


export default class AppSpecialTexts extends React.Component {
    /**
     *
     */
    constructor() {
        super();
        this.state = {
            cargando: false,
            texts: StorageSpecialTexts.texts,
            showEditForm: false,
            textToEdit: {},
            editForm: {},
        };
        StorageSpecialTexts.addListener(this.ListenerTextChange.bind(this))
    }

    /**
     *
     * @constructor
     */
    ListenerTextChange() {
        this.setState({
            texts: StorageSpecialTexts.texts,
            showEditForm: false,
        })
    }

    /**
     *
     * @param text
     * @param e
     */
    editText(text, e) {
        this.setState({
            showEditForm: true,
            textToEdit: text,
        })
    };

    static selectText(e) {
        return false;
    }

    newText(e) {
        this.setState({
            showEditForm: true,
            textToEdit: {},
        })
    }

    /**
     *
     */
    hideForm() {
        this.setState({
            showEditForm: false,
            textToEdit: {},
        })
    }

    /**
     *
     * @returns {Array}
     */
    renderButtons() {
        let response = [];
        let isFormActive = this.state.showEditForm;
        if (!isFormActive) {
            response.push(
                <a className={'btn btn-floating pull-right'} style={{marginBottom: '15px'}}
                   onClick={this.newText.bind(this)} key={`AppSpecialTexts--openForm`}><i
                    className={'material-icons'}>add</i></a>
            )
        } else {
            response.push(
                <a className={'btn btn-small pull-right'} style={{marginBottom: '15px'}}
                   onClick={this.hideForm.bind(this)} key={`AppSpecialTexts--hideForm`}><i
                    className={'material-icons'}>remove</i></a>
            )
        }

        return response;
    }

    removeSpecialTexts(text, e) {
        if (text !== null && !jQuery.isEmptyObject(text)) {
            let data = text;
            let new_url = StorageSpecialTexts.url + '/' + text.id + '/delete';
            data._token = StorageSpecialTexts.csrf;
            $.post({
                url: new_url,
                data: text,
            }).done(function (data) {
                console.log(data);
                StorageSpecialTexts.set(data);
            })
        }
    }

    /**
     *
     * @returns {*}
     */
    render() {
        if (StorageSpecialTexts.implement === '' || $.inArray(StorageSpecialTexts.implement, StorageSpecialTexts.allowedImplementations) < 0) {
            return <p>{StorageSpecialTexts.lang.FailedImplement}</p>
        }
        let component = this;

        let texts = component.state.texts.map(function (text) {
            return (
                <li className={'collection-item'} key={text.id.toString()}>
                    <label>{text.title}</label>
                    <div className={'secondary-content'}>
                        <a onClick={component.editText.bind(component, text)} style={{cursor: 'pointer'}}><i
                            className={'material-icons'} style={{color: 'var(--main-color)'}}>mode_edit</i></a>
                        <a style={{cursor: 'pointer'}} onClick={component.removeSpecialTexts.bind(component, text)}>
                            <i className={'material-icons'} style={{color: 'var(--main-color)'}}>delete</i>
                        </a>
                    </div>
                </li>
            )
        });

        return (
            <div>
                <div className="col s12">
                    <h5 className="header">{StorageSpecialTexts.lang.SpecialTexts}</h5>
                </div>

                <div className={'row'}>
                    <div className={'col s12'}>
                        <ul id={'special-text-list'} className={'collection col s12'}>
                            {texts}
                        </ul>
                        {this.renderButtons()}

                        <FormSpecialText text={this.state.textToEdit} show={this.state.showEditForm}
                                         creating={!(!!this.state.textToEdit.id)}/>
                    </div>
                </div>
            </div>
        )
    }
}
