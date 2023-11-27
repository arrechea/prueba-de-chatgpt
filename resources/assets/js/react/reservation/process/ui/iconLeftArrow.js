import React from "react";

const IconLeftArrow = ({
//   style = {},
  fill = "currentColor",
  width = "100%",
  height = "100%",
  className = "IconLeftArrow",
  viewBox = "0 0 511.991 511.991"
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
    <path fill={fill} d="M373.329,511.991c-2.813,0-5.604-1.104-7.708-3.292L130.954,263.366
	c-3.937-4.125-3.937-10.625,0-14.75L365.621,3.283c4.104-4.229,10.854-4.406,15.083-0.333c4.25,4.073,4.396,10.823,0.333,15.083
	L153.433,255.991L381.037,493.95c4.063,4.26,3.917,11.01-0.333,15.083C378.641,511.012,375.975,511.991,373.329,511.991z"/>

  </svg>
);

export default IconLeftArrow;