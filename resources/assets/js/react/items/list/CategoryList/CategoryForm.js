import React from 'react'
import StoreItemList from "../StoreItemList";

export default class CategoryForm extends React.Component {

    save() {
        let {
            category,
            id
        } = this.props;

        let url = null;


        if (category) {
            let base_url = StoreItemList.get('category_url');
            url = base_url.replace('_|_', category.id);
        } else {
            url = StoreItemList.get('category_new_url');
        }

        if (url) {
            let data = $(this.refs[`${id}--edit-form`]).serializeArray();
            $.ajax({
                method: 'post',
                url: url,
                data: data,
                success: function (category) {
                    let categories = StoreItemList.get('categories');

                    if (categories.length === 0) {
                        StoreItemList.set('selected_category', category);
                    }

                    if (categories.find(function (cat) {
                        return cat.id === category.id;
                    })) {
                        categories = categories.map(function (cat) {
                            if (cat.id === category.id) {
                                cat = category;
                            }

                            return cat;
                        });
                    } else {
                        categories.push(category);
                    }


                    StoreItemList.set('categories', categories)
                },
                error: function (e) {
                    displayErrorsToast(e, StoreItemList.getLang('MessageErrorCategorySave'));
                }
            });
        }
    }

    render() {
        let category = this.props.category ? this.props.category : null;
        let {
            id,
            title
        } = this.props;
        let csrf = StoreItemList.get('csrf');

        let inputId = function () {
            if (category) {
                return (
                    <input name={'id'} hidden={true} defaultValue={category.id}/>
                );
            } else {
                return null;
            }
        };

        return (
            <div className={'category-form'}>
                <div className={'modal modal-fixed-footer category-modal'} id={id}>
                    <div className={'modal-content'}>
                        <form id={'category-edit-form'} ref={`${id}--edit-form`}>
                            <input name={'_token'} defaultValue={csrf} hidden={true}/>
                            <h5>{title}</h5>
                            <div className={'input-field'}>
                                {inputId()}
                                <input type={'text'} id={'category-name'} name={'name'}
                                       defaultValue={category ? category.name : ''}/>
                                <label htmlFor={'category-name'}
                                       className={'active'}>{StoreItemList.getLang('Name')}</label>
                            </div>
                        </form>
                    </div>
                    <div className={'modal-footer'}>
                        <a className="modal-close btn edit-button save-button-footer"
                           href="#"> <i className="material-icons small">clear</i>
                            {StoreItemList.getLang('Cancel')}
                        </a>

                        <a className="modal-action modal-close btn edit-button"
                           onClick={this.save.bind(this)}>
                            <i className="material-icons small">done</i>
                            {StoreItemList.getLang('Save')}
                        </a>
                    </div>
                </div>
            </div>
        );
    }
}
