import React from "react";

const IconInfo = ({
//   style = {},
  fill = "currentColor",
  width = "496px",
  height = "496px",
  className = "IconInfo",
  viewBox = "0 0 496 496"
}) => (
  <svg 
    version="1.1"
    width={width}
    height={height}
    viewBox={viewBox}
    xmlns="http://www.w3.org/2000/svg"
    className={`svg-icon ${className || ""}`}
    xmlnsXlink="http://www.w3.org/1999/xlink"
  >
    <g id="Page-1" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
      <g id="info-circle" fill="#000000" fillRule="nonzero">
          <path d="M248,32 C366.621,32 464,128.075 464,248 C464,367.291 367.39,464 248,464 C128.756,464 32,367.438 32,248 C32,128.797 128.602,32 248,32 M248,0 C111.043,0 0,111.083 0,248 C0,384.997 111.043,496 248,496 C384.957,496 496,384.997 496,248 C496,111.083 384.957,0 248,0 Z M212,344 L224,344 L224,224 L212,224 C205.373,224 200,218.627 200,212 L200,204 C200,197.373 205.373,192 212,192 L260,192 C266.627,192 272,197.373 272,204 L272,344 L284,344 C290.627,344 296,349.373 296,356 L296,364 C296,370.627 290.627,376 284,376 L212,376 C205.373,376 200,370.627 200,364 L200,356 C200,349.373 205.373,344 212,344 Z M248,104 C230.327,104 216,118.327 216,136 C216,153.673 230.327,168 248,168 C265.673,168 280,153.673 280,136 C280,118.327 265.673,104 248,104 Z" id="Shape"></path>
    </g>
  </g>
  </svg>
);

export default IconInfo;