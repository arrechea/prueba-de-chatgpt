import React from "react";
import $ from 'jquery'
import uid from 'uid'
import StorageSpecialText from "./StorageSpecialText";

let allowedImplementations = StorageSpecialText.allowedImplementations;

export default class FormSpecialText extends React.Component {
    /**
     *
     * @returns {{text: null, show: boolean, creating: boolean}}
     */
    static get defaultProps() {
        return {
            text: null,
            show: false,
            creating: false,
        }
    }

    sendForm() {
        let form = this.refs.form;
        let data = $(form).find(':input').serializeArray();
        let implement = StorageSpecialText.implement;

        if ($.inArray(implement, allowedImplementations) >= 0) {
            let new_url = StorageSpecialText.url;
            if (this.props.text !== null && !jQuery.isEmptyObject(this.props.text)) {
                new_url += '/' + this.props.text.id;
                data.push({
                    name: 'id',
                    value: this.props.text.id,
                })
            }
            data.push({
                name: implement + '_id',
                value: $('input[name="id"]').val()
            });
            data.push({
                name: 'companies_id',
                value: $('input[name="companies_id"]').val()
            });
            data.push({
                name: 'brands_id',
                value: $('input[name="brands_id"]').val()
            });
            $.post({
                url: new_url,
                data: data,
            }).done(function (data) {
                console.log(data);
                StorageSpecialText.set(data);
            }).fail(function (e) {
                console.log(e.responseJSON.errors);
                alert(StorageSpecialText.lang.FailSaveMessage + '\n' + e.responseJSON.message);
            });
        } else {
            console.log(StorageSpecialText.lang.FailedImplement);
        }
    }

    render() {
        if (this.props.show === false) {
            return null;
        }
        let text = this.props.text;

        return (
            <div ref="form" key={uid()}>
                <input hidden={true} name={'_token'} defaultValue={StorageSpecialText.csrf}/>
                <div className={'col s12'}>
                    <div className={'row'}>
                        <div className={'col s12'}>
                            <label>{StorageSpecialText.lang.Title}</label>
                            <input type={'text'} name={'title'} id={'title'}
                                   required={true} defaultValue={text.title}/>
                        </div>
                        <div className={"col s6"}>
                            <label htmlFor={"tag"}>{StorageSpecialText.lang.Slug}</label>
                            <input type={"text"} id={"tag"} name={"tag"}
                                   defaultValue={text.tag} required={true}/>
                        </div>
                        <div className="col s6">
                            <label htmlFor="order">{StorageSpecialText.lang.Order}</label>
                            <input type={"number"} required={true}
                                   id={"order"}
                                   name={"order"}
                                   defaultValue={text.order}/>
                        </div>
                        <div className="col s12">
                            <label htmlFor="text-description">{StorageSpecialText.lang.Description}</label>
                            <textarea id={'text-description'} className={'materialize-textarea'}
                                      name={'description'}
                                      defaultValue={text.description}/>
                        </div>
                    </div>
                    <div className={'row'}>
                        <button className={'btn pull-right'} style={{marginBottom: '15px'}}
                                onClick={this.sendForm.bind(this)} type="button"><i
                            className={'material-icons right'}>save</i>{StorageSpecialText.lang.Save}
                        </button>
                    </div>
                </div>
            </div>
        )
    }
}
