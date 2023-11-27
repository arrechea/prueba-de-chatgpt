import React from 'react'
import StoreItemList from "../StoreItemList";

export default class CategoryDeleteForm extends React.Component {

    save() {
        let {
            category,
            id
        } = this.props;

        if (category) {
            let base_url = StoreItemList.get('category_delete_url');
            let url = base_url.replace('_|_', category.id);
            let data = $(this.refs[`${id}--delete-form`]).serializeArray();
            $.ajax({
                method: 'post',
                url: url,
                data: data,
                success: function (category) {
                    let categories = StoreItemList.get('categories');
                    let selected_category = StoreItemList.get('selected_category');

                    categories = categories.filter(function (cat) {
                        return cat.id !== category.id;
                    });

                    StoreItemList.set('categories', categories);

                    if (selected_category && category.id === selected_category.id) {
                        StoreItemList.set('selected_category', categories.length ? categories[0] : null);
                    }
                },
                error: function (e) {
                    displayErrorsToast(e, StoreItemList.getLang('MessageErrorCategoryDelete'));
                }
            })
        }
    }

    render() {
        let category = this.props.category ? this.props.category : null;
        let {
            id,
        } = this.props;
        let csrf = StoreItemList.get('csrf');

        return (
            <div className={'category-form'}>
                <div className={'modal modal-fixed-footer category-modal'} id={id}>
                    <div className={'modal-content'}>
                        <form id={'category-delete-form'} ref={`${id}--delete-form`}>
                            <input name={'_token'} defaultValue={csrf} hidden={true}/>
                            <input hidden={true} defaultValue={category.id} name={'id'}/>
                            <h5>{StoreItemList.getLang('DeleteCategory')}</h5>
                        </form>
                    </div>
                    <div className={'modal-footer'}>
                        <a className="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
                           href="#"> <i className="material-icons small">clear</i>
                            {StoreItemList.getLang('Cancel')}
                        </a>

                        <a className="modal-action modal-close waves-effect waves-green btn edit-button"
                           onClick={this.save.bind(this)}>
                            <i className="material-icons small">done</i>
                            {StoreItemList.getLang('Delete')}
                        </a>
                    </div>
                </div>
            </div>
        );
    }
}
