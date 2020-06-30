import EventRepository from './EventRepository';

const repositories = {
    'events': EventRepository,
}
export default {
    get: name => repositories[name]
};