在commit的时候在commit message里面使用#issue, 比如#1, gitlab就会自动关联issue 1跟这个commit的内容 
git commit  -m '已修正 #1'
那么如何跟随着commit关闭一个issue呢? 
在confirm merge的时候可以使用以下命令来关闭相关issue:
fixes #xxx
fixed #xxx
fix #xxx
closes #xxx
close #xxx
closed #xxx