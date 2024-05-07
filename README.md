WordPress Test:
(*) Goal
Set up a new WordPress DevEnv locally.

1. Create some dummy data, including posts , categories , and tags , you can ask FakePress for help.

2.A GitHub account, please push your code to your GitHub, and share your repository address.

(*) Requirements:

1. Create A Gutenberg Block to Display Advanced Search Form and Posts.

2. Update search parameters to URL when the search form is changed.

For example https://domain.com/search?q=Test&cat=2&tags[]=1&tags[]=2

3. Update Form states and search results from URL params on page refresh.

For example, there is q=Test in the URL, so the keyword input ﬁeld should autoﬁll by Test , and posts below should ﬁltered by Test , etc.

4. Use AJAX on search action.

5. Backup the database to the project root folder.

6. A handle over documents to help us quickly and easily understand your job. you can put it in README.md ﬁle in the project root folder within the

7. [Nice to have] Use TypeScript in Gutenberg Block Development There are 4 ﬁelds enabling you to get matching results for Search Page and the details are as follows:

1. Keyword - by searching the relevant keywords in the Posts

2. Category - A dropdown ﬁeld lists categories, by ﬁltering out the posts with deﬁned category options

3. tags - by ﬁltering out the posts with deﬁned tags

4. Pagination - by using value in pagination to narrow down relevant posts
