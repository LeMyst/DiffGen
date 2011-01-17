using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Runtime.Serialization;

namespace xDiffPatcher
{
    public partial class TreeNodeEx : TreeNode
    {
        public bool ActAsRadioGroup { get; set; }
        public bool Block { get; set; }

        public TreeNodeEx() : base()
        {
        }

        public TreeNodeEx(string text)
            : base(text)
        {
        }

        protected TreeNodeEx(SerializationInfo serializationInfo, StreamingContext context)
            : base(serializationInfo, context)
        {
        }

        public TreeNodeEx(string text, TreeNode[] children)
            : base(text, children)
        {
        }

        public TreeNodeEx(string text, int imageIndex, int selectedImageIndex)
            : base(text, imageIndex, selectedImageIndex)
        {
        }

        public TreeNodeEx(string text, int imageIndex, int selectedImageIndex, TreeNode[] children)
            : base(text, imageIndex, selectedImageIndex, children)
        {
        }
    }

    public partial class PatchList : TreeView
    {
        public PatchList()
        {
            //base.InitializeComponent();
        }

        protected override void OnPaint(PaintEventArgs pe)
        {
            base.OnPaint(pe);
        }

        protected override void OnAfterCheck(TreeViewEventArgs e)
        {
            TreeNodeEx node = (TreeNodeEx)e.Node;

            if (this.CheckBoxes)
            {
                if (node.Block) return;

                if (node.Nodes != null && node.Nodes.Count > 0 && !node.Checked)
                {
                    foreach (TreeNodeEx n in e.Node.Nodes)
                    {
                        n.Block = true;
                        n.Checked = false;
                        n.Block = false;
                    }

                    base.OnAfterCheck(e);
                    return;
                }

                if (node.Nodes != null && node.Nodes.Count > 0 && node.Checked) //e.Node is TreeNodeEx && !((TreeNodeEx) e.Node).Block && e.Node.Nodes != null && e.Node.Nodes.Count > 0)
                {
                    bool check = false;

                    foreach (TreeNode n in node.Nodes)
                    {
                        if (n.Checked)
                            check = true;
                    }

                    if (!check) //no checked child ! tick first child :D
                    {
                        ((TreeNodeEx)node.Nodes[0]).Block = true;
                        node.Nodes[0].Checked = true;
                        ((TreeNodeEx)node.Nodes[0]).Block = false;
                    }

                    node.Block = false;
                    base.OnAfterCheck(e);
                    return;
                }

                if (node.Parent != null && node.Parent is TreeNodeEx)
                {
                    if (node.Checked)
                    {
                        TreeNodeEx n = (TreeNodeEx)e.Node.Parent;
                        n.Block = true;
                        n.Checked = true; //Check parent if child is checked
                        n.Block = false;

                        if (n.ActAsRadioGroup && !n.Block)
                        {
                            n.Block = true; // to prevent unwanted recursion
                            foreach (TreeNodeEx m in n.Nodes)
                            {
                                if (m != e.Node)
                                {
                                    m.Block = true;
                                    m.Checked = false;
                                    m.Block = false;
                                }
                            }
                            n.Block = false;
                        }
                    }
                    else
                    {
                        bool check = false;

                        foreach (TreeNode n in node.Parent.Nodes)
                        {
                            if (n.Checked)
                                check = true;
                        }

                        if (!check)
                        {
                            ((TreeNodeEx)node.Parent).Block = true;
                            node.Parent.Checked = false;
                            ((TreeNodeEx)node.Parent).Block = false;
                        }
                    }
                }
            }

            base.OnAfterCheck(e);

        }

        protected override void OnDrawNode(DrawTreeNodeEventArgs e)
        {
            base.OnDrawNode(e);
        }
    }
}
