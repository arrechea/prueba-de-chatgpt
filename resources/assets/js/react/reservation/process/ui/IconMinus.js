import React from "react";

const IconMinus = ({
//   style = {},
  fill = "currentColor",
  width = "100%",
  height = "100%",
  className = "IconMinus",
  viewBox = "0 0 31.444 31.444"
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
    <path fill={fill} d="M1.111,16.832C0.492,16.832,0,16.325,0,15.706c0-0.619,0.492-1.111,1.111-1.111H30.3
	c0.619,0,1.127,0.492,1.127,1.111c0,0.619-0.508,1.127-1.127,1.127H1.111z"/>
  </svg>
);

export default IconMinus;