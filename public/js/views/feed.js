import {postTemplate} from "../components/post.js";

export function renderFeed(posts) {
    return posts.map(postTemplate).join('')
}