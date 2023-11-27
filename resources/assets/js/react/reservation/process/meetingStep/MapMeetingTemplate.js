import React from 'react';
import StoreMapSelector from "./map/StoreMapSelector";
import ProcessingImage from "../common/ProcessingImage";
import CellGridMapSelector from "./map/CellGridMapSelector";
import NextStepProductsTemplate from "./NextStepProductsTemplate";
import StoreReservation from "../StoreReservation";
import MapDetails from "./map/MapDetails";
import MeetingResume from "../thankyouStep/MeetingResume";
import MapInformation from "./map/MapInformation";

export default class MapMeetingTemplate extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            loading: true,
            map: null,
            tab: false,
        };
        this.toggleClass = this.toggleClass.bind(this);
        StoreMapSelector.addListener(this.ListenerStoreMap.bind(this))
    }

    static get defaultProps() {
        return {
            meeting: null,
        }
    }

    toggleClass() {
        let {tab} = this.state;

        this.setState({
            tab: !tab,
        });
    }

    ListenerStoreMap() {
        this.setState({
            map: StoreMapSelector.get('map')
        });
    }


    componentDidMount() {
        let component = this;
        let {meeting} = component.props;
        let {map, reservation} = meeting;

        StoreMapSelector.init(map, reservation, () => {
            component.setState({
                loading: false
            })
        });
    }

    /**
     *
     * @param cell
     * @returns {null}
     */
    getCellType(cell) {
        if (cell && cell.object && cell.object.positions) {
            return cell.object.positions.type;
        }
        return null;
    }

    /**
     *
     * @returns {XML}
     */
    render() {
        let {map, loading, tab} = this.state;
        if (loading) {
            return <ProcessingImage/>
        }
        let component = this;
        let {meeting} = this.props;
        let mapInfo = meeting.map;
        let cellNumber = 1;
        let map_objectsSelected = StoreReservation.get('map_objectsSelected');

        let lang = StoreReservation.get('lang');
        let translateConfirm = Object.assign({}, lang);
        translateConfirm.reservation = lang['confirm.reservation'];
        translateConfirm.locale = lang['confirm.locale'];
        let location = StoreReservation.get('location');

        return (
            <div className="MapMeeting">
                <div className="MapMeeting--left">
                    <div className="gs-mapMeeting">
                        <div className="MapMeeting__header">
                            <MapInformation meeting={meeting}/>
                            <div className="gs-title">{lang['meeting.title']}</div>
                            <div className="gs-subtitle">{lang['meeting.map.explain']}</div>
                            <MapDetails lang={lang} positions={StoreMapSelector.get('map_positionList')}/>
                        </div>
                        <div className="MapMeeting--canvas" style={{
                            backgroundImage: `url(${mapInfo.image_background})`
                        }}>
                            {map.map((row, rowIndex) => {
                                //This a row
                                let cells = null;
                                if (row.length) {
                                    cells = row.map((cell, cellIndex) => {
                                        let cellCurrentNumber = cell && component.getCellType(cell) === 'public' ? cellNumber : null;
                                        if (cellCurrentNumber !== null) {
                                            cellNumber++;
                                        }

                                        return (
                                            <CellGridMapSelector
                                                rowIndex={rowIndex}
                                                cellIndex={cellIndex}
                                                cellNumber={cellCurrentNumber}
                                                cell={cell}
                                                key={`MapMeeting--cell--${rowIndex}--${cellIndex}`}
                                            />
                                        )
                                    })
                                }
                                return (
                                    <div
                                        className="CellRow"
                                        data-row={rowIndex}
                                        key={`MapMeeting--row--${rowIndex}`}
                                    >
                                        {cells}
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </div>
                <div className="MapMeeting--right">
                    <div
                        className={'check-out-container MapMeeting__summary ' + (tab ? 'summaryTab-open' : 'summaryTab-close')}>
                        <MeetingResume
                            date={meeting.start_date}
                            staff={meeting.staff}
                            meeting={meeting}
                            lang={translateConfirm}
                            map_objectsSelected={map_objectsSelected}
                            isMap={true}
                            toggleClass={this.toggleClass}
                        />
                    </div>
                </div>
                <NextStepProductsTemplate/>
            </div>
        );
    }
}
