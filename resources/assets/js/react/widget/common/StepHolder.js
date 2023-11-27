import React from 'react'
import {gsap} from "gsap";
import BuqIcon from "../ui/icons/BuqIcon";
import StoreWidget from "../StoreWidget";
import BackHolder from "./BackHolder";


export default class StepHolder extends React.Component {

    constructor() {
        super();

    }

    /**
     *
     * @param prevProps
     * @param prevState
     * @returns {*}
     */
    getSnapshotBeforeUpdate(prevProps, prevState) {
        let {
            step
        } = this.props;
        if (step && !prevProps.step) {
            //apertura
            return 'open'
        } else if (!step && prevProps.step) {
            //cieree
            return 'close'
        } else {
            return null;
        }
    }

    componentDidUpdate(prevProps, prevState, animation) {
        let holder = document.querySelector('.WidgetBUQ--StepHolder');
        let lower = document.querySelector('.WidgetBUQ--LowerBar');

        if (animation === 'open') {
            gsap.to(
                holder,
                1,
                {
                    height: lower.getBoundingClientRect().top + lower.clientHeight,
                }
            );
        } else if (animation === 'close') {
            //
        }
    }

    static _close() {
        if (StoreWidget.step) {
            gsap.to(
                document.querySelector('.WidgetBUQ--StepHolder'),
                1,
                {
                    height: 0,
                    onComplete: () => {
                        StoreWidget.set('backholder', 'none', () => {
                            StoreWidget.goToStep(null);
                        });
                    }
                }
            );
        }
    }


    render() {
        let {
            step
        } = this.props;
        if (!step) {
            return null;
        }

        return (
            <div
                className="WidgetBUQ--StepHolder"
            >
                <div className="WidgetBUQ--StepHolder--top">
                    <div className="WidgetBUQ--poweredBy">
                        <p>Powered by</p>
                        <BuqIcon />
                     </div>
                    <div
                        className="WidgetBUQ--close"
                        onClick={StepHolder._close}
                    >
                       <i className="fal fa-times"></i>
                    </div>
                </div>
                <div className="WidgetBUQ--StepHolder--body">
                    <BackHolder/>
                    <div className="WidgetBUQ--StepHolder--body--cont">
                        {step}
                    </div>
                </div>
            </div>
        )
    }
}
