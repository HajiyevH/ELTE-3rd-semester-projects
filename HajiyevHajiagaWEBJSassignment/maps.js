export const easyMap1 = [
    ['empty', 'mountainSW', 'empty', 'empty', 'oasis'],
    ['empty', 'empty', 'empty', 'bridgeNS', 'oasis'],
    ['bridgeNS', 'empty', 'mountainNW', 'empty', 'empty'],
    ['empty', 'empty', 'empty', 'oasis', 'empty'],
    ['empty', 'empty', 'mountainNE', 'empty', 'empty']
];
export const easyMap2 = [
    ['oasis', 'empty', 'bridgeEW', 'empty', 'empty'],
    ['empty', 'mountainNW', 'empty', 'empty', 'mountainNW'],
    ['bridgeNS', 'oasis', 'mountainNE', 'empty', 'empty'],
    ['empty', 'empty', 'empty', 'oasis', 'empty'],
    ['empty', 'empty', 'empty', 'empty', 'empty']
];
export const easyMap3 = [
    ['empty', 'empty', 'bridgeEW', 'empty', 'empty'],
    ['empty', 'empty', 'empty', 'empty', 'bridgeNS'],
    ['empty', 'mountainNW', 'bridgeNS', 'empty', 'empty'],
    ['empty', 'oasis', 'empty', 'empty', 'empty'],
    ['empty', 'bridgeEW', 'empty', 'empty', 'empty']
];
export const easyMap4 = [
    ['empty', 'empty', 'empty', 'bridgeEW', 'empty'],
    ['empty', 'empty', 'empty', 'empty', 'empty'],
    ['bridgeNS', 'empty', 'mountainSW', 'empty', 'mountainSW'],
    ['empty', 'empty', 'empty', 'empty', 'empty'],
    ['empty', 'empty', 'oasis', 'mountainNE', 'empty']
];
export const easyMap5 = [
    ['empty', 'empty', 'bridgeEW', 'empty', 'empty'],
    ['empty', 'mountainSE', 'empty', 'empty', 'empty'],
    ['bridgeNS', 'empty', 'empty', 'mountainNE', 'empty'],
    ['empty', 'empty', 'bridgeNS', 'oasis', 'empty'],
    ['empty', 'mountainNW', 'empty', 'empty', 'empty']
];
export const hardMap1 = [
    ['empty', 'mountainSW', 'oasis', 'oasis', 'empty', 'bridgeEW', 'empty'],
    ['bridgeNS', 'empty', 'empty', 'empty', 'empty', 'empty', 'empty'],
    ['empty', 'empty', 'bridgeNS', 'empty', 'empty', 'empty', 'empty'],
    ['empty', 'empty', 'empty', 'mountainNE', 'empty', 'empty', 'empty'],
    ['mountainNE', 'empty', 'mountainSW', 'empty', 'bridgeEW', 'empty', 'oasis'],
    ['empty', 'empty', 'empty', 'empty', 'empty', 'empty', 'empty'],
    ['empty', 'empty', 'empty', 'bridgeEW', 'empty', 'empty', 'empty']
];
export const hardMap2 = [
    ['empty', 'empty', "oasis", 'empty', 'empty', 'empty', 'empty'],
    ['bridgeNS', 'empty', 'bridgeEW', 'empty', 'empty', 'mountainNW', 'empty'],
    ['empty', 'empty', 'bridgeEW', 'empty', 'empty', 'empty', 'bridgeNS'],
    ['mountainSE', 'empty', 'empty', 'empty', 'empty', 'empty', 'empty'],
    ['empty', "oasis", 'empty', 'mountainSW', 'empty', 'empty', 'empty'],
    ['empty', 'mountainSE', 'empty', 'empty', 'empty', 'empty', 'empty'],
    ['empty', 'empty', "oasis", 'empty', 'empty', 'empty', 'empty']
];export const hardMap3 = [
    ['empty', 'empty', 'bridgeEW', 'empty', 'empty', 'empty', 'empty'],
    ["empty", 'empty', 'empty', 'empty', 'empty', 'empty', 'bridgeNS'],
    ['oasis', 'empty', 'mountainNE', 'empty', 'empty', 'empty', 'empty'],
    ['empty', 'empty', 'empty', 'empty', 'empty', 'empty', 'empty'],
    ['empty', "oasis", 'mountainNE', 'empty', 'bridgeEW', 'empty', 'empty'],
    ['bridgeNS', 'empty', 'empty', 'empty', 'empty', 'mountainSW', 'empty'],
    ['empty', 'empty', "oasis", 'mountainNE', 'empty', 'empty', 'empty']
];export const hardMap4 = [
    ['empty', 'empty', 'empty', 'empty', 'empty', 'empty', 'empty'],
    ['empty', 'empty', 'empty', 'bridgeNS', 'empty', 'mountainNW', 'empty'],
    ['empty', 'empty', 'mountainNE', 'empty', 'empty', 'empty', 'empty'],
    ['empty', 'bridgeEW', 'empty', "oasis", 'empty', 'bridgeEW', 'empty'],
    ['empty', 'empty', 'mountainNW', 'empty', 'mountainSW', 'empty', 'empty'],
    ['bridgeNS', 'empty', 'empty', 'empty', 'empty', 'mountainNE', 'empty'],
    ['empty', 'empty', 'empty', 'empty', 'empty', 'empty', 'empty']
];export const hardMap5 = [
    ['empty', 'empty', 'empty', 'empty', 'empty', 'empty', 'empty'],
    ['empty', 'empty', 'empty', 'empty', 'empty', 'mountainSE', 'empty'],
    ['empty', 'bridgeEW', 'bridgeEW', 'empty', 'mountainSW', 'empty', 'empty'],
    ['empty', 'empty', 'empty', 'empty', 'empty', 'empty', 'empty'],
    ['empty', 'empty', 'mountainSE', 'empty', "oasis", 'empty', 'empty'],
    ['empty', 'mountainNW', 'empty', 'bridgeNS', 'empty', 'empty', 'empty'],
    ['empty', 'empty', 'empty', 'empty', 'empty', 'empty', 'empty']
];

export function getNextTileAndDirections(currentTile) {
    let nextDirections = [];
    let nextTile = '';

    switch (currentTile) {
        case 'empty': 
            nextTile = 'straightNS';
            nextDirections = ['north', 'south'];
            break;
        case 'straightNS': 
            nextTile = 'straightEW';
            nextDirections = ['east', 'west'];
            break;
        case 'straightEW': 
            nextTile = 'curve_railNE';
            nextDirections = ['north', 'east'];
            break;
        case 'curve_railNE': 
            nextTile = 'curve_railSE';
            nextDirections = ['south', 'east'];
            break;
        case 'curve_railSE': 
            nextTile = 'curve_railSW';
            nextDirections = ['south', 'west'];
            break;
        case 'curve_railSW': 
            nextTile = 'curve_railNW';
            nextDirections = ['north', 'west'];
            break;
        case 'curve_railNW': 
            nextTile = 'empty';
            nextDirections = ['none','none'];
            break;
        case 'mountainNE': 
            nextTile = 'mountain_railNE';
            nextDirections = ['north', 'east'];
            break;
        case 'mountain_railNE': 
            nextTile = 'mountainNE';
            nextDirections = ['none','none'];
            break;
        case 'mountainSE': 
            nextTile = 'mountain_railSE';
            nextDirections = ['south', 'east'];
            break;
        case 'mountain_railSE': 
            nextTile = 'mountainSE';
            nextDirections = ['none','none'];
            break;
        case 'mountainSW': 
            nextTile = 'mountain_railSW';
            nextDirections = ['south', 'west'];
            break;
        case 'mountain_railSW': 
            nextTile = 'mountainSW';
            nextDirections = ['none','none'];
            break;
        case 'mountainNW': 
            nextTile = 'mountain_railNW';
            nextDirections = ['north', 'west'];
            break;
        case 'mountain_railNW': 
            nextTile = 'mountainNW';
            nextDirections = ['none','none'];
            break;
        case 'bridgeEW': 
            nextTile = 'bridge_railEW';
            nextDirections = ['east', 'west'];
            break;
        case 'bridge_railEW': 
            nextTile = 'bridgeEW';
            nextDirections = ['none','none'];
            break;
        case 'bridgeNS': 
            nextTile = 'bridge_railNS';
            nextDirections = ['north', 'south'];
            break;
        case 'bridge_railNS': 
            nextTile = 'bridgeNS';
            nextDirections = ['none','none'];
            break;
        case 'oasis': 
            nextTile = 'oasis';
            nextDirections = ['none','none'];
            break;
    }

    return { nextTile, nextDirections };
}
export function getTileImageSrc(tile) {
    switch (tile) {
        case 'empty': return 'pics/tiles/empty.png';
        case 'straightNS': return 'pics/tiles/straightNS.png';
        case 'straightEW': return 'pics/tiles/straightEW.png';
        case 'curve_railNE': return 'pics/tiles/curveNE.png';
        case 'curve_railSE': return 'pics/tiles/curveSE.png';
        case 'curve_railSW': return 'pics/tiles/curveSW.png';
        case 'curve_railNW': return 'pics/tiles/curveNW.png';
        case 'mountainNE': return 'pics/tiles/mountainNE.png';
        case 'mountain_railNE': return 'pics/tiles/mountain_railNE.png';
        case 'mountainSE': return 'pics/tiles/mountainSE.png';
        case 'mountain_railSE': return 'pics/tiles/mountain_railSE.png';
        case 'mountainSW': return 'pics/tiles/mountainSW.png';
        case 'mountain_railSW': return 'pics/tiles/mountain_railSW.png';
        case 'mountainNW': return 'pics/tiles/mountainNW.png';
        case 'mountain_railNW': return 'pics/tiles/mountain_railNW.png';
        case 'bridgeEW': return 'pics/tiles/bridgeEW.png';
        case 'bridge_railEW': return 'pics/tiles/bridge_railEW.png';
        case 'bridgeNS': return 'pics/tiles/bridgeNS.png';
        case 'bridge_railNS': return 'pics/tiles/bridge_railNS.png';
        case 'oasis': return 'pics/tiles/oasis.png';
        default: return '';
    }
}