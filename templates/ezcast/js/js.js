const arr = [
    'test1',
    'test2',
    'test3',
    'test4',
    'test5'
];

let i = 0;

app.get('/route', (req, res) => {
    res.redirect(arr[i++ % arr.length])
});
