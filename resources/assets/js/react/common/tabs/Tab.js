import React from 'react'
export const Tab = (props) => {

   let style = !props.front && props.id.slug === "courtesy" ? {display:'none'} : {};

    return (
        <li className="TabsGafaFit--tab" style={style}>
            <a className={`TabsGafaFit--tab-link ${props.linkClassName} TabsGafaFit--tab-link--${props.id.slug} ${props.isActive ? 'TabsGafaFit--tab-link__active' : ''} `}
               onClick={(event) => {
                   event.preventDefault();
                   props.onClick(props.tabIndex, props.id);
               }}>
                {props.image ? <img src={props.image} alt="" className="TabsGafaFit--tab-link--image"/> : null}
                {props.text}
            </a>
        </li>
    )
};
