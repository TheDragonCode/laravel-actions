import fs from 'fs'
import path from 'path'
import dotenv from 'dotenv'

import {viteBundler} from '@vuepress/bundler-vite'
import {defaultTheme} from '@vuepress/theme-default'
import {defineUserConfig} from 'vuepress'

dotenv.config()

function getChildren(folder, sort = 'asc') {
    const extension = ['.md']
    const names = ['index.md', 'readme.md']

    const dir = `${__dirname}/../${folder}`

    return fs
        .readdirSync(path.join(dir))
        .filter(item => fs.statSync(path.join(dir, item)).isFile() && !names.includes(item.toLowerCase()) && extension.includes(path.extname(item)))
        .sort((a, b) => {
            a = resolveNumeric(a)
            b = resolveNumeric(b)

            if (a < b) return sort === 'asc' ? -1 : 1
            if (a > b) return sort === 'asc' ? 1 : -1

            return 0
        }).map(item => `/${folder}/${item}`)
}

function resolveNumeric(value) {
    const sub = value.substring(0, value.indexOf('.'))

    const num = Number(sub)

    return isNaN(num) ? value : num
}

const hostname = 'deploy-operations.dragon-code.pro'

export default defineUserConfig({
    bundler: viteBundler(),

    lang: 'en-US',
    title: 'Laravel Deploy Operations',
    description: 'Performing any actions during the deployment process',

    head: [['link', {rel: 'icon', href: `https://${hostname}/images/logo.svg`}]],

    theme: defaultTheme({
        hostname,
        base: '/',

        logo: `https://${hostname}/images/logo.svg`,

        repo: 'https://github.com/TheDragonCode/laravel-deploy-operations',
        repoLabel: 'GitHub',
        docsRepo: 'https://github.com/TheDragonCode/laravel-deploy-operations',
        docsBranch: 'main',
        docsDir: 'docs',

        contributors: false,
        editLink: true,

        navbar: [
            {
                text: '6.x',
                children: [
                    {
                        text: '6.x',
                        link: '/getting-started/installation.md'
                    },
                    {
                        text: '5.x',
                        link: 'https://github.com/TheDragonCode/laravel-deploy-operations/blob/5.x/docs/index.md'
                    }, {
                        text: '4.x',
                        link: 'https://github.com/TheDragonCode/laravel-deploy-operations/blob/4.x/docs/index.md'
                    }, {
                        text: '3.x',
                        link: 'https://github.com/TheDragonCode/laravel-deploy-operations/blob/3.x/docs/index.md'
                    },
                ]
            }],

        sidebarDepth: 1,

        sidebar: [
            {
                text: 'Getting Started', children: [
                    '/index.md',
                    '/getting-started/installation.md',
                    '/guide/basic.md',
                    '/upgrade-guide/index.md'
                ]
            },
            {
                text: 'Guide',
                children: [
                    '/guide/running.md',
                    '/guide/creating.md',
                    '/guide/status.md',
                    '/guide/rollback.md',
                    '/guide/customize-stub.md',
                ]
            },
            {
                text: 'Helpers',
                children: getChildren('helpers')
            },
            {
                text: 'Extras',
                children: [
                    '/extras/database-data-dumper.md'
                ]
            }
        ]
    })
})
